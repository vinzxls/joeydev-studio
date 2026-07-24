<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, string $code)
    {
        abort_unless(session('track_verified_'.$code), 403);

        $order = Order::where('order_code', $code)->firstOrFail();

        if ($order->status !== 'selesai') {
            return back()->withErrors(['rating' => 'Rating hanya bisa diberikan setelah order selesai.']);
        }
        if ($order->review) {
            return back()->withErrors(['rating' => 'Anda sudah memberikan rating untuk order ini.']);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $order->review()->create($data);

        return back()->with('status', 'Terima kasih! Rating Anda sudah terkirim dan akan tampil setelah dimoderasi.');
    }
}
