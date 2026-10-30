@extends('layouts.admin')

@section('title', 'Review — Admin JoeyDev')
@section('crumb', 'Review')

@section('content')
    <div class="ad-page-head">
        <div>
            <p class="ad-eyebrow">Moderasi</p>
            <h1 class="ad-title">Moderasi Review<span>.</span></h1>
            <p style="margin-top:8px;font-size:12.5px;color:#667085">
                Review baru disembunyikan sampai Anda menampilkannya.
                Isi review tidak bisa diedit demi kredibilitas.
            </p>
        </div>
        <p class="ad-mono text-[10px] uppercase tracking-[0.14em] text-[#98a2b3]">
            {{ $reviews->total() }} ulasan
        </p>
    </div>

    <div class="ad-cols-3 mt-5" style="gap:14px">
        @forelse ($reviews as $review)
            <article class="ad-card" style="display:flex;flex-direction:column;padding:16px 18px 18px;border-color:{{ $review->is_approved ? '#abefc6' : '#e4e7ec' }}">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px">
                    <p style="font-size:13px;letter-spacing:0.06em;color:#f79009">
                        @for ($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                    </p>
                    @if ($review->is_approved)
                        <span class="ad-chip selesai">Tampil</span>
                    @else
                        <span class="ad-chip baru">Disembunyikan</span>
                    @endif
                </div>

                @if ($review->comment)
                    <p style="margin-top:11px;flex:1;font-size:12.5px;line-height:1.65;color:#344054">
                        &ldquo;{{ $review->comment }}&rdquo;
                    </p>
                @else
                    <p style="margin-top:11px;flex:1;font-size:12.5px;font-style:italic;color:#98a2b3">
                        (tanpa komentar)
                    </p>
                @endif

                <div style="margin-top:14px;padding-top:11px;border-top:1px solid #f2f4f7;display:flex;align-items:center;gap:8px">
                    <span class="ad-mono" style="display:flex;align-items:center;justify-content:center;width:24px;height:24px;flex:none;border-radius:4px;background:#eff4ff;color:#1d4ed8;font-size:10px;font-weight:700">
                        {{ mb_strtoupper(mb_substr($review->displayName(), 0, 1)) }}
                    </span>
                    <div style="min-width:0">
                        <p style="font-size:12px;font-weight:700;color:#101828;line-height:1.2">{{ $review->displayName() }}</p>
                        <p style="font-size:10.5px;color:#98a2b3;margin-top:2px">
                            {{ $review->order->subject->name ?? '—' }} &middot;
                            <a href="{{ route('admin.orders.show', $review->order) }}"
                               class="ad-mono" style="color:#1d4ed8;font-weight:700;text-decoration:none">{{ $review->order->order_code }}</a>
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}" style="margin-top:12px">
                    @csrf @method('PATCH')
                    @if ($review->is_approved)
                        <button class="ad-btn ad-btn-ghost" style="width:100%">Sembunyikan dari publik</button>
                    @else
                        <button class="ad-btn" style="width:100%">Tampilkan di publik</button>
                    @endif
                </form>
            </article>
        @empty
            <p style="grid-column:1/-1;padding:36px 20px;text-align:center;font-size:12.5px;color:#98a2b3">
                Belum ada review masuk.
            </p>
        @endforelse
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
@endsection
