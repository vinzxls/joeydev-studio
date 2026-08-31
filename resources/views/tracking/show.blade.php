@extends('layouts.app')

@section('title', 'Progress '.$order->order_code.' — JoeyDev Studio')

@section('content')
<div class="mx-auto max-w-3xl px-4 pb-10 pt-24 md:pt-32">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <p class="font-mono text-xs font-bold uppercase tracking-[0.25em] text-redup">Progress Order</p>
            <h1 class="mt-1 font-display text-2xl font-extrabold tracking-tight text-tinta sm:text-3xl">
                <span class="font-mono text-aksen">{{ $order->order_code }}</span>
            </h1>
            <p class="mt-1 text-redup">{{ $order->title }}</p>
        </div>
        <span class="inline-flex w-fit items-center rounded-full px-4 py-1.5 text-sm font-bold
            @switch($order->status)
                @case('selesai') bg-emerald-100 text-emerald-700 @break
                @case('dibatalkan') bg-rose-100 text-rose-700 @break
                @case('dikerjakan') bg-sky-100 text-sky-700 @break
                @case('review_revisi') bg-amber-100 text-amber-700 @break
                @default bg-garis text-tinta
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
        <x-jd.kartu class="mt-9 flex items-center p-5 sm:p-6">
            @foreach ($steps as $key => $label)
                @php $idx = array_search($key, $stepKeys); $done = $currentIdx !== false && $idx <= $currentIdx; @endphp
                <div class="flex flex-1 flex-col items-center text-center">
                    <span class="flex h-10 w-10 items-center justify-center rounded-kartu text-sm font-extrabold transition {{ $done ? 'bg-aksen text-kertas' : 'bg-kertas border border-garis text-redup' }}">
                        {{ $done ? '✓' : $idx + 1 }}
                    </span>
                    <span class="mt-2 text-[11px] font-bold sm:text-xs {{ $done ? 'text-aksen' : 'text-redup' }}">{{ $label }}</span>
                </div>
                @if (! $loop->last)
                    <div class="mx-1 mb-6 h-1.5 flex-1 rounded-full {{ $currentIdx !== false && $idx < $currentIdx ? 'bg-aksen' : 'bg-garis' }}"></div>
                @endif
            @endforeach
        </x-jd.kartu>
    @else
        <div class="mt-7 rounded-kartu border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-700">Order ini dibatalkan.</div>
    @endif

    {{-- Detail --}}
    <div class="mt-6 grid gap-6 sm:grid-cols-2">
        <x-jd.kartu class="p-6">
            <h2 class="font-display font-extrabold text-tinta">Detail Order<span class="text-aksen">.</span></h2>
            <dl class="mt-4 space-y-2.5 text-sm">
                <div class="flex justify-between gap-3"><dt class="text-redup">Layanan</dt><dd class="text-right font-bold text-tinta">{{ $order->subject->name }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-redup">Deadline</dt><dd class="font-bold text-tinta">{{ $order->deadline->translatedFormat('d F Y') }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-redup">Tanggal Order</dt><dd class="font-bold text-tinta">{{ $order->created_at->translatedFormat('d F Y') }}</dd></div>
                @if ($order->attachments->isNotEmpty())
                    <div><dt class="text-redup">Lampiran</dt>
                        <dd class="mt-1.5 space-y-1">
                            @foreach ($order->attachments as $file)
                                <span class="block truncate rounded-kartu bg-garis px-3 py-1.5 text-xs font-medium">📎 {{ $file->original_name }}</span>
                            @endforeach
                        </dd>
                    </div>
                @endif
            </dl>
        </x-jd.kartu>

        <x-jd.kartu class="p-6">
            <h2 class="font-display font-extrabold text-tinta">Rincian Harga<span class="text-emerald-500">.</span></h2>
            <dl class="mt-4 space-y-2 text-sm">
                @if ($order->final_price === null)
                    <p class="text-redup">Harga menunggu konfirmasi admin (layanan custom).</p>
                @else
                    <div class="flex justify-between"><dt class="text-redup">Harga dasar</dt><dd class="font-bold">Rp{{ number_format($order->base_price, 0, ',', '.') }}</dd></div>
                    @foreach ([['scale_label', 'scale_value'], ['complexity_label', 'complexity_value'], ['urgency_label', 'urgency_value']] as [$l, $v])
                        @if ($order->$l)
                            <div class="flex justify-between text-redup"><dt>× {{ $order->$l }}</dt><dd>×{{ rtrim(rtrim(number_format($order->$v, 2), '0'), '.') }}</dd></div>
                        @endif
                    @endforeach
                    @foreach ($order->addons as $addon)
                        <div class="flex justify-between text-redup"><dt>+ {{ $addon->name }}</dt><dd>Rp{{ number_format($addon->price, 0, ',', '.') }}</dd></div>
                    @endforeach
                    <div class="flex items-center justify-between border-t border-dashed border-garis pt-2.5"><dt class="font-bold text-tinta">Total</dt><dd class="text-lg font-extrabold text-aksen">Rp{{ number_format($order->final_price, 0, ',', '.') }}</dd></div>
                @endif
                <div class="flex justify-between"><dt class="text-redup">Status pembayaran</dt>
                    <dd><span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : ($order->payment_status === 'dp' ? 'bg-amber-100 text-amber-700' : 'bg-garis text-tinta') }}">{{ $order->paymentStatusLabel() }}</span></dd>
                </div>
            </dl>
        </x-jd.kartu>
    </div>

    {{-- Riwayat progress --}}
    <x-jd.kartu class="mt-6 p-6">
        <h2 class="font-display font-extrabold text-tinta">Riwayat Progress<span class="text-aksen">.</span></h2>
        <ol class="mt-5 space-y-5 border-l-2 border-garis pl-5">
            @forelse ($order->statusLogs as $log)
                <li class="relative">
                    <span class="absolute -left-[27px] top-1 h-3 w-3 rounded-full {{ $loop->first ? 'bg-aksen ring-4 ring-aksen/20' : 'bg-garis' }}"></span>
                    <p class="text-sm font-bold text-tinta">{{ $log->statusLabel() }}</p>
                    @if ($log->note)
                        <p class="mt-0.5 text-sm text-redup">{{ $log->note }}</p>
                    @endif
                    <p class="mt-1 text-xs text-redup">{{ $log->created_at->translatedFormat('d F Y, H:i') }}</p>
                </li>
            @empty
                <li class="text-sm text-redup">Belum ada riwayat.</li>
            @endforelse
        </ol>
    </x-jd.kartu>

    {{-- Rating --}}
    @if ($order->status === 'selesai')
        <x-jd.kartu class="mt-6 p-6">
            @if ($order->review)
                <h2 class="font-display font-extrabold text-tinta">Rating Anda<span class="text-amber-500">.</span></h2>
                <p class="mt-3 text-2xl text-amber-400">
                    @for ($i = 1; $i <= 5; $i++){{ $i <= $order->review->rating ? '★' : '☆' }}@endfor
                </p>
                @if ($order->review->comment)
                    <p class="mt-1.5 text-sm text-redup">“{{ $order->review->comment }}”</p>
                @endif
                <p class="mt-3 text-xs text-redup">Terima kasih atas ulasannya! 💜</p>
            @else
                <h2 class="font-display font-extrabold text-tinta">Beri Rating &amp; Ulasan<span class="text-amber-500">.</span></h2>
                <p class="mt-1.5 text-sm text-redup">Order Anda sudah selesai — bagaimana pengalaman Anda?</p>
                @error('rating')
                    <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                @enderror
                <form method="POST" action="{{ route('review.store', $order->order_code) }}" class="mt-4">
                    @csrf
                    <div class="flex flex-row-reverse justify-end gap-1 text-4xl" id="star-picker">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="star-{{ $i }}" class="peer hidden" required>
                            <label for="star-{{ $i }}" class="cursor-pointer text-garis transition peer-checked:text-amber-400 hover:text-amber-300 [&:hover~label]:text-amber-300">★</label>
                        @endfor
                    </div>
                    <textarea name="comment" rows="3" maxlength="2000" placeholder="Ulasan Anda (opsional)"
                              class="mt-4 w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 text-sm transition focus:border-aksen focus:outline-none focus:ring-2 focus:ring-aksen/10">{{ old('comment') }}</textarea>
                    <x-jd.tombol-utama type="submit" class="mt-4">Kirim Rating</x-jd.tombol-utama>
                </form>
            @endif
        </x-jd.kartu>
    @endif
</div>
@endsection
