@extends('layouts.app')

@section('title', 'Cek Progress Order — JoeyDev Studio')

@section('content')
<div class="mx-auto max-w-md px-4 pb-14 pt-24 md:pt-36">
    <h1 class="text-center text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
        Cek <span class="bg-gradient-to-r from-sky-500 to-indigo-500 bg-clip-text text-transparent">Progress</span><span class="text-sky-500">.</span>
    </h1>
    <p class="mt-3 text-center text-slate-500">Masukkan kode order dan nomor WA/email yang Anda pakai saat memesan.</p>

    <form method="POST" action="{{ route('track.check') }}" class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
        @csrf
        <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Kode Order</label>
            <input type="text" name="order_code" value="{{ old('order_code') }}" required placeholder="JD-2026-XXXXXXXX"
                   class="w-full rounded-2xl border border-slate-300 px-4 py-3 font-mono text-sm uppercase transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
            @error('order_code')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="mt-4">
            <label class="mb-1.5 block text-sm font-bold text-slate-700">Nomor WhatsApp atau Email</label>
            <input type="text" name="contact" value="{{ old('contact') }}" required placeholder="08xxxxxxxxxx atau email@anda.com"
                   class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
            @error('contact')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit"
                class="mt-6 flex w-full items-center justify-center gap-2 rounded-full bg-violet-600 py-3.5 font-bold text-white shadow-lg shadow-violet-600/30 transition hover:-translate-y-0.5 hover:bg-violet-700">
            Lihat Progress
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
        </button>
    </form>
</div>
@endsection
