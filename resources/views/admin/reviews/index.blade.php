@extends('layouts.admin')

@section('title', 'Review — Admin JoeyDev')

@section('content')
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Moderasi Review<span class="text-amber-500">.</span></h1>
    <p class="mt-1.5 text-sm text-slate-500">Review baru disembunyikan sampai Anda menampilkannya. Isi review tidak bisa diedit demi kredibilitas.</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($reviews as $review)
            <div class="flex flex-col rounded-3xl border {{ $review->is_approved ? 'border-emerald-200' : 'border-slate-200' }} bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <p class="text-lg text-amber-400">@for ($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor</p>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $review->is_approved ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $review->is_approved ? 'Tampil' : 'Disembunyikan' }}
                    </span>
                </div>
                @if ($review->comment)
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">“{{ $review->comment }}”</p>
                @else
                    <p class="mt-3 flex-1 text-sm italic text-slate-400">(tanpa komentar)</p>
                @endif
                <p class="mt-4 flex items-center gap-2 text-xs text-slate-400">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-violet-100 text-xs font-extrabold text-violet-600">{{ mb_strtoupper(mb_substr($review->displayName(), 0, 1)) }}</span>
                    {{ $review->displayName() }} · {{ $review->order->subject->name ?? '' }} ·
                    <a href="{{ route('admin.orders.show', $review->order) }}" class="font-mono font-bold text-violet-600 hover:underline">{{ $review->order->order_code }}</a>
                </p>
                <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}" class="mt-4">
                    @csrf @method('PATCH')
                    <button class="w-full rounded-full py-2.5 text-sm font-bold transition {{ $review->is_approved ? 'border border-slate-300 text-slate-600 hover:bg-slate-100' : 'bg-violet-600 text-white shadow-md shadow-violet-600/30 hover:bg-violet-700' }}">
                        {{ $review->is_approved ? 'Sembunyikan dari publik' : 'Tampilkan di publik' }}
                    </button>
                </form>
            </div>
        @empty
            <p class="text-slate-400 sm:col-span-3">Belum ada review masuk.</p>
        @endforelse
    </div>

    <div class="mt-5">{{ $reviews->links() }}</div>
@endsection
