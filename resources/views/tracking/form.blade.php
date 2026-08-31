@extends('layouts.app')

@section('title', 'Cek Progress Order — JoeyDev Studio')

@section('content')
<div class="mx-auto max-w-md px-4 pb-14 pt-24 md:pt-36">
    <x-jd.label-section path="/cek-progress" class="justify-center">Cek Progress</x-jd.label-section>
    <h1 class="mt-3 text-center font-display text-3xl font-extrabold tracking-tight text-tinta sm:text-4xl">
        Cek <span class="text-aksen">Progress</span><span class="text-aksen">.</span>
    </h1>
    <p class="mt-3 text-center text-redup">Masukkan kode order dan nomor WA/email yang Anda pakai saat memesan.</p>

    <form method="POST" action="{{ route('track.check') }}" class="mt-8">
        @csrf
        <x-jd.kartu class="p-6 sm:p-7">
            <div>
                <label class="mb-1.5 block text-sm font-bold text-tinta">Kode Order</label>
                <input type="text" name="order_code" value="{{ old('order_code') }}" required placeholder="JD-2026-XXXXXXXX"
                       class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 font-mono text-sm uppercase transition focus:border-aksen focus:outline-none focus:ring-2 focus:ring-aksen/10">
                @error('order_code')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label class="mb-1.5 block text-sm font-bold text-tinta">Nomor WhatsApp atau Email</label>
                <input type="text" name="contact" value="{{ old('contact') }}" required placeholder="08xxxxxxxxxx atau email@anda.com"
                       class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 text-sm transition focus:border-aksen focus:outline-none focus:ring-2 focus:ring-aksen/10">
                @error('contact')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <x-jd.tombol-utama type="submit" class="mt-6 w-full">
                Lihat Progress
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </x-jd.tombol-utama>
        </x-jd.kartu>
    </form>
</div>
@endsection
