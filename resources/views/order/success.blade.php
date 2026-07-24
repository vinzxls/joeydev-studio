@extends('layouts.app')

@section('title', 'Order Berhasil — JoeyDev Studio')

@section('content')
<div class="mx-auto max-w-2xl px-4 pb-14 pt-24 text-center md:pt-36">
    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-emerald-100 text-3xl">🎉</span>
    <h1 class="mt-5 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
        Order <span class="bg-gradient-to-r from-emerald-500 to-teal-500 bg-clip-text text-transparent">Berhasil</span><span class="text-emerald-500">.</span>
    </h1>
    <p class="mt-2 text-slate-500">Simpan kode order Anda — kode ini dipakai untuk mengecek progress pengerjaan.</p>

    <div class="mt-7 rounded-3xl border-2 border-dashed border-violet-300 bg-violet-50 p-7">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-400">Kode Order Anda</p>
        <p class="mt-2 font-mono text-4xl font-extrabold tracking-wider text-violet-700">{{ $order->order_code }}</p>
    </div>

    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 text-left shadow-sm sm:p-7">
        <h2 class="font-extrabold text-slate-900">Ringkasan Order<span class="text-violet-600">.</span></h2>
        <dl class="mt-4 space-y-2.5 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">Layanan</dt><dd class="font-bold text-slate-900">{{ $order->subject->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Judul</dt><dd class="font-bold text-slate-900">{{ $order->title }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Deadline</dt><dd class="font-bold text-slate-900">{{ $order->deadline->translatedFormat('d F Y') }}</dd></div>
            @if ($order->final_price !== null)
                <div class="flex items-center justify-between border-t border-dashed border-slate-200 pt-3"><dt class="text-slate-500">Total Estimasi</dt><dd class="text-xl font-extrabold text-violet-600">Rp{{ number_format($order->final_price, 0, ',', '.') }}</dd></div>
            @else
                <div class="flex justify-between border-t border-dashed border-slate-200 pt-3"><dt class="text-slate-500">Total</dt><dd class="font-bold text-slate-600">Menunggu penawaran admin (layanan custom)</dd></div>
            @endif
        </dl>
    </div>

    <p class="mt-6 text-sm text-slate-500">
        Admin akan menghubungi Anda via WhatsApp (<span class="font-bold text-slate-700">{{ $order->client_wa }}</span>) untuk konfirmasi order dan pembayaran DP.
    </p>

    <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
        <a href="{{ route('track.show', $order->order_code) }}"
           class="rounded-full bg-violet-600 px-8 py-3.5 font-bold text-white shadow-lg shadow-violet-600/30 transition hover:-translate-y-0.5 hover:bg-violet-700">Lihat Progress Order</a>
        <a href="{{ route('home') }}"
           class="rounded-full border border-slate-300 bg-white px-8 py-3.5 font-bold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-400">Kembali ke Beranda</a>
    </div>
</div>
@endsection
