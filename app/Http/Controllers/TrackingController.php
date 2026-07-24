<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function form()
    {
        return view('tracking.form');
    }

    /** Verifikasi kode order + WA/email agar order tidak bisa diintip orang lain. */
    public function check(Request $request)
    {
        $data = $request->validate([
            'order_code' => ['required', 'string', 'max:30'],
            'contact' => ['required', 'string', 'max:150'],
        ]);

        $code = strtoupper(trim($data['order_code']));
        $contact = strtolower(trim($data['contact']));
        $contactDigits = preg_replace('/\D/', '', $contact);

        $order = Order::where('order_code', $code)->first();

        $matches = $order && (
            strtolower($order->client_email) === $contact
            || ($contactDigits !== '' && $order->client_wa === $contactDigits)
        );

        if (! $matches) {
            return back()->withInput()->withErrors([
                'order_code' => 'Kombinasi kode order dan WA/email tidak ditemukan. Periksa kembali.',
            ]);
        }

        session()->put('track_verified_'.$order->order_code, true);

        return redirect()->route('track.show', $order->order_code);
    }

    public function show(string $code)
    {
        abort_unless(session('track_verified_'.$code), 403);

        $order = Order::with(['subject', 'addons', 'attachments', 'statusLogs', 'review'])
            ->where('order_code', $code)
            ->firstOrFail();

        return view('tracking.show', compact('order'));
    }
}
