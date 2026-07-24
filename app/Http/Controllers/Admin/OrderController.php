<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Subject;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('subject')
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
        ]);
    }

    public function show(Order $order)
    {
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
