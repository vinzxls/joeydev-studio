@extends('layouts.app')

@section('title', 'Progress '.$order->order_code.' — JoeyDev Studio')

@section('content')
<div class="mx-auto max-w-3xl px-4 pb-10 pt-24 md:pt-32">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Progress Order</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                <span class="font-mono text-violet-600">{{ $order->order_code }}</span>
            </h1>
            <p class="mt-1 text-slate-500">{{ $order->title }}</p>
        </div>
        <span class="inline-flex w-fit items-center rounded-full px-4 py-1.5 text-sm font-bold
            @switch($order->status)
                @case('selesai') bg-emerald-100 text-emerald-700 @break
                @case('dibatalkan') bg-rose-100 text-rose-700 @break
                @case('dikerjakan') bg-sky-100 text-sky-700 @break
                @case('review_revisi') bg-amber-100 text-amber-700 @break
                @default bg-slate-200 text-slate-600
            @endswitch">
            {{ $order->statusLabel() }}
        </span>
    </div>

    {{-- Timeline visual --}}
    @php
        $steps = ['menunggu_konfirmasi' => 'Menunggu Konfirmasi', 'dikerjakan' => 'Dikerjakan', 'review_revisi' => 'Review/Revisi', 'selesai' => 'Selesai'];
        $stepKeys = array_keys($steps);
        $currentIdx = array_search($order->status, $stepKeys);
    @endphp
    @if ($order->status !== 'dibatalkan')
        <div class="mt-9 flex items-center rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            @foreach ($steps as $key => $label)
                @php $idx = array_search($key, $stepKeys); $done = $currentIdx !== false && $idx <= $currentIdx; @endphp
                <div class="flex flex-1 flex-col items-center text-center">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl text-sm font-extrabold transition {{ $done ? 'bg-violet-600 text-white shadow-md shadow-violet-600/30' : 'bg-slate-100 text-slate-400' }}">
                        {{ $done ? '✓' : $idx + 1 }}
                    </span>
                    <span class="mt-2 text-[11px] font-bold sm:text-xs {{ $done ? 'text-violet-600' : 'text-slate-400' }}">{{ $label }}</span>
                </div>
                @if (! $loop->last)
                    <div class="mx-1 mb-6 h-1.5 flex-1 rounded-full {{ $currentIdx !== false && $idx < $currentIdx ? 'bg-violet-600' : 'bg-slate-100' }}"></div>
                @endif
            @endforeach
        </div>
    @else
        <div class="mt-7 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-700">Order ini dibatalkan.</div>
    @endif

    {{-- Detail --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-2">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-extrabold text-slate-900">Detail Order<span class="text-sky-500">.</span></h2>
            <dl class="mt-4 space-y-2.5 text-sm">
                <div class="flex justify-between gap-3"><dt class="text-slate-500">Layanan</dt><dd class="text-right font-bold text-slate-900">{{ $order->subject->name }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-500">Deadline</dt><dd class="font-bold text-slate-900">{{ $order->deadline->translatedFormat('d F Y') }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-500">Tanggal Order</dt><dd class="font-bold text-slate-900">{{ $order->created_at->translatedFormat('d F Y') }}</dd></div>
                @if ($order->attachments->isNotEmpty())
                    <div><dt class="text-slate-500">Lampiran</dt>
                        <dd class="mt-1.5 space-y-1">
                            @foreach ($order->attachments as $file)
                                <span class="block truncate rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-medium">📎 {{ $file->original_name }}</span>
                            @endforeach
                        </dd>
                    </div>
                @endif
            </dl>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-extrabold text-slate-900">Rincian Harga<span class="text-emerald-500">.</span></h2>
            <dl class="mt-4 space-y-2 text-sm">
                @if ($order->final_price === null)
                    <p class="text-slate-500">Harga menunggu konfirmasi admin (layanan custom).</p>
                @else
                    <div class="flex justify-between"><dt class="text-slate-500">Harga dasar</dt><dd class="font-bold">Rp{{ number_format($order->base_price, 0, ',', '.') }}</dd></div>
                    @foreach ([['scale_label', 'scale_value'], ['complexity_label', 'complexity_value'], ['urgency_label', 'urgency_value']] as [$l, $v])
                        @if ($order->$l)
                            <div class="flex justify-between text-slate-500"><dt>× {{ $order->$l }}</dt><dd>×{{ rtrim(rtrim(number_format($order->$v, 2), '0'), '.') }}</dd></div>
                        @endif
                    @endforeach
                    @foreach ($order->addons as $addon)
                        <div class="flex justify-between text-slate-500"><dt>+ {{ $addon->name }}</dt><dd>Rp{{ number_format($addon->price, 0, ',', '.') }}</dd></div>
                    @endforeach
                    <div class="flex items-center justify-between border-t border-dashed border-slate-200 pt-2.5"><dt class="font-bold text-slate-900">Total</dt><dd class="text-lg font-extrabold text-violet-600">Rp{{ number_format($order->final_price, 0, ',', '.') }}</dd></div>
                @endif
                <div class="flex justify-between"><dt class="text-slate-500">Status pembayaran</dt>
                    <dd><span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : ($order->payment_status === 'dp' ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-600') }}">{{ $order->paymentStatusLabel() }}</span></dd>
                </div>
            </dl>
        </section>
    </div>

    {{-- Riwayat progress --}}
    <section class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="font-extrabold text-slate-900">Riwayat Progress<span class="text-violet-600">.</span></h2>
        <ol class="mt-5 space-y-5 border-l-2 border-violet-100 pl-5">
            @forelse ($order->statusLogs as $log)
                <li class="relative">
                    <span class="absolute -left-[27px] top-1 h-3 w-3 rounded-full {{ $loop->first ? 'bg-violet-600 ring-4 ring-violet-100' : 'bg-slate-300' }}"></span>
                    <p class="text-sm font-bold text-slate-900">{{ $log->statusLabel() }}</p>
                    @if ($log->note)
                        <p class="mt-0.5 text-sm text-slate-600">{{ $log->note }}</p>
                    @endif
                    <p class="mt-1 text-xs text-slate-400">{{ $log->created_at->translatedFormat('d F Y, H:i') }}</p>
                </li>
            @empty
                <li class="text-sm text-slate-400">Belum ada riwayat.</li>
            @endforelse
        </ol>
    </section>

    {{-- Rating --}}
    @if ($order->status === 'selesai')
        <section class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @if ($order->review)
                <h2 class="font-extrabold text-slate-900">Rating Anda<span class="text-amber-500">.</span></h2>
                <p class="mt-3 text-2xl text-amber-400">
                    @for ($i = 1; $i <= 5; $i++){{ $i <= $order->review->rating ? '★' : '☆' }}@endfor
                </p>
                @if ($order->review->comment)
                    <p class="mt-1.5 text-sm text-slate-600">“{{ $order->review->comment }}”</p>
                @endif
                <p class="mt-3 text-xs text-slate-400">Terima kasih atas ulasannya! 💜</p>
            @else
                <h2 class="font-extrabold text-slate-900">Beri Rating &amp; Ulasan<span class="text-amber-500">.</span></h2>
                <p class="mt-1.5 text-sm text-slate-500">Order Anda sudah selesai — bagaimana pengalaman Anda?</p>
                @error('rating')
                    <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                @enderror
                <form method="POST" action="{{ route('review.store', $order->order_code) }}" class="mt-4">
                    @csrf
                    <div class="flex flex-row-reverse justify-end gap-1 text-4xl" id="star-picker">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="star-{{ $i }}" class="peer hidden" required>
                            <label for="star-{{ $i }}" class="cursor-pointer text-slate-200 transition peer-checked:text-amber-400 hover:text-amber-300 [&:hover~label]:text-amber-300">★</label>
                        @endfor
                    </div>
                    <textarea name="comment" rows="3" maxlength="2000" placeholder="Ulasan Anda (opsional)"
                              class="mt-4 w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">{{ old('comment') }}</textarea>
                    <button type="submit"
                            class="mt-4 rounded-full bg-violet-600 px-8 py-3 font-bold text-white shadow-lg shadow-violet-600/30 transition hover:-translate-y-0.5 hover:bg-violet-700">Kirim Rating</button>
                </form>
            @endif
        </section>
    @endif
</div>
@endsection
