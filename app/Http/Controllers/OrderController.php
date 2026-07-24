<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Order;
use App\Models\PricingMultiplier;
use App\Models\Subject;
use App\Services\PriceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        return view('order.create', [
            'subjects' => Subject::active()->get(),
            'scales' => PricingMultiplier::active()->ofType('scale')->get(),
            'complexities' => PricingMultiplier::active()->ofType('complexity')->get(),
            'urgencies' => PricingMultiplier::active()->ofType('urgency')->get(),
            'addons' => Addon::active()->get(),
            'preselect' => $request->query('subject'),
        ]);
    }

    /** Endpoint AJAX estimasi harga real-time. */
    public function estimate(Request $request, PriceCalculator $calculator)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'scale_id' => ['nullable', 'integer'],
            'complexity_id' => ['nullable', 'integer'],
            'urgency_id' => ['nullable', 'integer'],
            'addon_ids' => ['nullable', 'array'],
            'addon_ids.*' => ['integer'],
        ]);

        $subject = Subject::active()->findOrFail($data['subject_id']);
        $result = $calculator->calculate(
            $subject,
            $data['scale_id'] ?? null,
            $data['complexity_id'] ?? null,
            $data['urgency_id'] ?? null,
            $data['addon_ids'] ?? [],
        );

        return response()->json([
            'is_custom' => $result['is_custom'],
            'base_price' => $result['base_price'],
            'subtotal' => $result['subtotal'] ?? null,
            'addons_total' => $result['addons_total'],
            'final_price' => $result['final_price'],
            'breakdown' => [
                'scale' => $result['scale']?->only(['label', 'value']),
                'complexity' => $result['complexity']?->only(['label', 'value']),
                'urgency' => $result['urgency']?->only(['label', 'value']),
                'addons' => $result['addons']->map(fn ($a) => $a->only(['name', 'price']))->values(),
            ],
        ]);
    }

    public function store(Request $request, PriceCalculator $calculator)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:10000'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'scale_id' => ['nullable', 'integer'],
            'complexity_id' => ['nullable', 'integer'],
            'urgency_id' => ['nullable', 'integer'],
            'addon_ids' => ['nullable', 'array'],
            'addon_ids.*' => ['integer'],
            'client_name' => ['required', 'string', 'max:100'],
            'client_wa' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'client_email' => ['required', 'email', 'max:150'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,png,jpg,jpeg,zip', 'max:10240'], // 10 MB
        ], [
            'client_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'attachments.*.mimes' => 'Lampiran harus berupa PDF, DOC/DOCX, gambar, atau ZIP.',
            'attachments.*.max' => 'Ukuran tiap lampiran maksimal 10 MB.',
        ]);

        $subject = Subject::active()->findOrFail($data['subject_id']);

        // Subject non-custom wajib memilih semua faktor harga
        if (! $subject->is_custom) {
            $request->validate([
                'scale_id' => ['required'],
                'complexity_id' => ['required'],
                'urgency_id' => ['required'],
            ], [
                'scale_id.required' => 'Pilih skala pekerjaan.',
                'complexity_id.required' => 'Pilih tingkat kompleksitas.',
                'urgency_id.required' => 'Pilih urgensi deadline.',
            ]);
        }

        $price = $calculator->calculate(
            $subject,
            $data['scale_id'] ?? null,
            $data['complexity_id'] ?? null,
            $data['urgency_id'] ?? null,
            $data['addon_ids'] ?? [],
        );

        $order = DB::transaction(function () use ($data, $subject, $price, $request) {
            $order = Order::create([
                'order_code' => Order::generateCode(),
                'client_name' => $data['client_name'],
                'client_wa' => preg_replace('/\D/', '', $data['client_wa']),
                'client_email' => strtolower($data['client_email']),
                'subject_id' => $subject->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'deadline' => $data['deadline'],
                'base_price' => $price['base_price'],
                'scale_label' => $price['scale']?->label,
                'scale_value' => $price['scale']?->value,
                'complexity_label' => $price['complexity']?->label,
                'complexity_value' => $price['complexity']?->value,
                'urgency_label' => $price['urgency']?->label,
                'urgency_value' => $price['urgency']?->value,
                'addons_total' => $price['addons_total'],
                'final_price' => $price['final_price'],
            ]);

            foreach ($price['addons'] as $addon) {
                $order->addons()->create([
                    'addon_id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price,
                ]);
            }

            foreach ($request->file('attachments', []) as $file) {
                $path = $file->store('attachments/'.$order->order_code, 'public');
                $order->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }

            $order->statusLogs()->create([
                'status' => 'menunggu_konfirmasi',
                'note' => 'Order diterima sistem. Admin akan segera menghubungi Anda untuk konfirmasi.',
            ]);

            return $order;
        });

        // Izinkan klien melihat halaman sukses & tracking di sesi ini
        session()->put('track_verified_'.$order->order_code, true);

        return redirect()->route('order.success', $order->order_code);
    }

    public function success(string $code)
    {
        abort_unless(session('track_verified_'.$code), 403);
        $order = Order::with(['subject', 'addons'])->where('order_code', $code)->firstOrFail();

        return view('order.success', compact('order'));
    }
}
