@extends('layouts.app')

@section('title', 'Order Berhasil — JoeyDev Studio')

@section('content')
<div class="mx-auto max-w-2xl px-4 pb-14 pt-24 text-center md:pt-36">
    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-kartu border border-garis bg-permukaan text-3xl">🎉</span>
    <h1 class="mt-5 font-display text-3xl font-extrabold tracking-tight text-tinta sm:text-4xl">
        Order <span class="text-aksen">Berhasil</span><span class="text-aksen">.</span>
    </h1>
    <p class="mt-2 text-redup">Simpan kode order Anda — kode ini dipakai untuk mengecek progress pengerjaan.</p>

    <div class="mt-7 rounded-kartu border border-dashed border-garis bg-permukaan p-7">
        <p class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-redup">Kode Order Anda</p>
        <p class="mt-2 font-mono text-4xl font-extrabold tracking-wider text-tinta">{{ $order->order_code }}</p>
    </div>

    <x-jd.kartu class="mt-6 p-6 text-left sm:p-7">
        <h2 class="font-display font-extrabold text-tinta">Ringkasan Order<span class="text-aksen">.</span></h2>
        <dl class="mt-4 space-y-2.5 text-sm">
            <div class="flex justify-between"><dt class="text-redup">Layanan</dt><dd class="font-bold text-tinta">{{ $order->subject->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-redup">Judul</dt><dd class="font-bold text-tinta">{{ $order->title }}</dd></div>
            <div class="flex justify-between"><dt class="text-redup">Deadline</dt><dd class="font-bold text-tinta">{{ $order->deadline->translatedFormat('d F Y') }}</dd></div>
            @if ($order->final_price !== null)
                <div class="flex items-center justify-between border-t border-dashed border-garis pt-3"><dt class="text-redup">Total Estimasi</dt><dd class="text-xl font-extrabold text-aksen">Rp{{ number_format($order->final_price, 0, ',', '.') }}</dd></div>
            @else
                <div class="flex justify-between border-t border-dashed border-garis pt-3"><dt class="text-redup">Total</dt><dd class="font-bold text-tinta">Menunggu penawaran admin (layanan custom)</dd></div>
            @endif
        </dl>
    </x-jd.kartu>

    <p class="mt-6 text-sm text-redup">
        Admin akan menghubungi Anda via WhatsApp (<span class="font-bold text-tinta">{{ $order->client_wa }}</span>) untuk
        mengonfirmasi detail kebutuhan, harga final, dan pembayaran DP sebelum pengerjaan dimulai.
        @if ($order->final_price !== null)
            Angka di atas masih berupa estimasi awal.
        @endif
    </p>

    <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
        <x-jd.tombol-utama :href="route('track.show', $order->order_code)">Lihat Progress Order</x-jd.tombol-utama>
        <x-jd.tombol-sekunder :href="route('home')">Kembali ke Beranda</x-jd.tombol-sekunder>
    </div>
</div>
@endsection
