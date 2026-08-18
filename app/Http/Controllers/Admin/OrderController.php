<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $arsip = $request->boolean('arsip');

        $orders = Order::with('subject')
            ->when($arsip, fn ($q) => $q->onlyTrashed())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('subject'), fn ($q) => $q->where('subject_id', $request->subject))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->q.'%';
                $q->where(fn ($w) => $w
                    ->where('order_code', 'like', $term)
                    ->orWhere('client_name', 'like', $term)
                    ->orWhere('title', 'like', $term));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'subjects' => Subject::orderBy('sort_order')->get(),
            'arsip' => $arsip,
            'jumlahArsip' => Order::onlyTrashed()->count(),
        ]);
    }

    public function show(string $order)
    {
        $order = Order::withTrashed()->findOrFail($order);
        $order->load(['subject', 'addons', 'attachments', 'statusLogs', 'review']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Order::STATUSES))],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $order->update(['status' => $data['status']]);
        $order->statusLogs()->create([
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
        ]);

        return back()->with('status', 'Status order diperbarui ke "'.Order::STATUSES[$data['status']].'".');
    }

    /**
     * Arsipkan order (soft delete): hilang dari daftar & statistik, tapi masih
     * bisa dipulihkan. Lampiran sengaja tidak dihapus agar pemulihan utuh.
     */
    public function destroy(Request $request, Order $order)
    {
        // Konfirmasi ketik kode order, agar tidak terarsip karena salah klik
        $request->validate([
            'order_code' => ['required', 'same:confirm_code'],
        ], [
            'order_code.same' => 'Kode order yang Anda ketik tidak cocok. Order tidak diarsipkan.',
        ]);

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('status', 'Order '.$order->order_code.' ('.$order->client_name.') diarsipkan. '
                .'Bisa dipulihkan kapan saja lewat daftar Arsip.');
    }

    /** Kembalikan order yang diarsipkan ke daftar aktif. */
    public function restore(string $order)
    {
        $order = Order::onlyTrashed()->findOrFail($order);
        $order->restore();

        return redirect()->route('admin.orders.show', $order)
            ->with('status', 'Order '.$order->order_code.' ('.$order->client_name.') dipulihkan.');
    }

    /** Hapus permanen beserta riwayat, add-on, review, dan file lampirannya. */
    public function forceDestroy(Request $request, string $order)
    {
        $order = Order::onlyTrashed()->findOrFail($order);

        $request->validate([
            'order_code' => ['required', 'same:confirm_code'],
        ], [
            'order_code.same' => 'Kode order yang Anda ketik tidak cocok. Order tidak dihapus.',
        ]);

        foreach ($order->attachments as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        $code = $order->order_code;
        $order->forceDelete();

        return redirect()->route('admin.orders.index', ['arsip' => 1])
            ->with('status', 'Order '.$code.' beserta riwayat dan lampirannya dihapus permanen.');
    }

    public function updatePayment(Request $request, Order $order)
    {
        $data = $request->validate([
            'payment_status' => ['required', 'in:'.implode(',', array_keys(Order::PAYMENT_STATUSES))],
            'final_price' => ['nullable', 'integer', 'min:0'], // untuk set harga order custom
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $order->update(array_filter([
            'payment_status' => $data['payment_status'],
            'final_price' => $data['final_price'] ?? null,
            'admin_note' => $data['admin_note'] ?? null,
        ], fn ($v) => $v !== null));

        return back()->with('status', 'Data pembayaran/harga diperbarui.');
    }
}
