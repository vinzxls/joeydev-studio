@extends('layouts.admin')

@section('title', 'Dashboard — Admin JoeyDev')
@section('crumb', 'Dashboard')

@section('top-actions')
    <a href="{{ route('admin.orders.index') }}" class="ad-btn-ghost">Semua order</a>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="ad-btn-solid">
        Lihat situs
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" style="width:11px;height:11px" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
        </svg>
    </a>
@endsection

@section('content')
    <div class="ad-page-head">
        <div>
            <p class="ad-eyebrow">Ringkasan</p>
            <h1 class="ad-title">Dashboard<span>.</span></h1>
        </div>
        <p class="ad-mono text-[10px] uppercase tracking-[0.14em] text-[#98a2b3]">
            Diperbarui {{ now()->translatedFormat('d M Y') }} &middot; {{ now()->format('H:i') }} WIT
        </p>
    </div>

    {{-- Statistik: angka besar tabular, label mono, tanpa chip pastel --}}
    <div class="ad-stats">
        <div class="ad-stat">
            <p class="ad-stat-k">Order aktif</p>
            <span class="ad-stat-v">{{ str_pad((string) $stats['active_orders'], 2, '0', STR_PAD_LEFT) }}</span>
            <p class="ad-stat-d flat">{{ $stats['active_orders'] > 0 ? 'Sedang dikerjakan' : 'Tidak ada antrian' }}</p>
        </div>

        <div class="ad-stat">
            <p class="ad-stat-k">Order bulan ini</p>
            <span class="ad-stat-v">{{ str_pad((string) $stats['month_orders'], 2, '0', STR_PAD_LEFT) }}</span>
            <p class="ad-stat-d up">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" style="width:10px;height:10px" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                </svg>
                {{ $stats['month_orders'] }} masuk bulan ini
            </p>
        </div>

        <div class="ad-stat">
            <p class="ad-stat-k">Pendapatan bulan ini</p>
            <span class="ad-stat-v">
                @if ($stats['month_revenue'] >= 1000000)
                    Rp{{ number_format($stats['month_revenue'] / 1000000, 2, ',', '.') }}<span style="font-size:1rem;letter-spacing:0">jt</span>
                @else
                    Rp{{ number_format($stats['month_revenue'] / 1000, 0, ',', '.') }}<span style="font-size:1rem;letter-spacing:0">rb</span>
                @endif
            </span>
            <p class="ad-stat-d up">Total lunas</p>
        </div>

        <div class="ad-stat">
            <p class="ad-stat-k">Rata-rata rating</p>
            <span class="ad-stat-v">
                @if ($stats['avg_rating'] > 0)
                    {{ number_format($stats['avg_rating'], 1, ',', '.') }}<span style="font-size:1rem;letter-spacing:0">&#9733;</span>
                @else
                    &mdash;
                @endif
            </span>
            <p class="ad-stat-d flat">
                dari {{ $stats['pending_reviews'] > 0 ? $stats['pending_reviews'].' menunggu moderasi' : 'ulasan disetujui' }}
            </p>
        </div>
    </div>

    <div class="ad-grid-2">

        {{-- Order terbaru --}}
        <section class="ad-card">
            <div class="ad-card-head">
                <h2 class="ad-card-title">Order terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="ad-card-link">Lihat semua &rarr;</a>
            </div>
            <div style="overflow-x:auto">
                <table class="ad-tbl">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Klien</th>
                            <th>Status</th>
                            <th>Bayar</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="ad-code">{{ $order->order_code }}</a>
                                </td>
                                <td>
                                    <span class="ad-client">{{ $order->client_name }}</span>
                                    <span class="ad-sub">{{ $order->subject->name ?? '—' }}</span>
                                </td>
                                <td><x-ad.chip :nilai="$order->status" /></td>
                                <td><x-ad.chip jenis="bayar" :nilai="$order->payment_status" /></td>
                                <td class="ad-money">
                                    {{ $order->final_price !== null ? 'Rp'.number_format($order->final_price, 0, ',', '.') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding:32px 20px;text-align:center;color:#98a2b3">
                                    Belum ada order.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="ad-stack">

            {{-- Subject terpopuler --}}
            <section class="ad-card">
                <div class="ad-card-head">
                    <h2 class="ad-card-title">Subject terpopuler</h2>
                </div>
                <div style="padding:6px 0 10px">
                    @php $maksSubject = (int) ($topSubjects->max('total') ?: 1); @endphp
                    @forelse ($topSubjects as $row)
                        <div class="ad-bar-row">
                            <div>
                                <p class="ad-bar-name">{{ $row->subject->name ?? '—' }}</p>
                                <div class="ad-bar-track">
                                    <div class="ad-bar-fill" style="width: {{ round(($row->total / $maksSubject) * 100) }}%"></div>
                                </div>
                            </div>
                            <span class="ad-bar-val">{{ $row->total }}</span>
                        </div>
                    @empty
                        <p style="padding:14px 20px;font-size:12.5px;color:#98a2b3">Belum ada data.</p>
                    @endforelse
                </div>
            </section>

            {{-- Ringkasan --}}
            <section class="ad-card">
                <div class="ad-card-head">
                    <h2 class="ad-card-title">Ringkasan</h2>
                </div>
                <div style="padding:6px 0 10px">
                    <div class="ad-sum"><span>Total order</span><b>{{ $stats['total_orders'] }}</b></div>
                    <div class="ad-sum"><span>Order selesai</span><b>{{ $stats['completed_orders'] }}</b></div>
                    <div class="ad-sum">
                        <span>Total pendapatan (lunas)</span>
                        <b>Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</b>
                    </div>
                    <div class="ad-sum">
                        <span>Review menunggu moderasi</span>
                        <a href="{{ route('admin.reviews.index') }}">{{ $stats['pending_reviews'] }}</a>
                    </div>
                </div>
            </section>

        </div>
    </div>

    {{-- Tren 6 bulan --}}
    <section class="ad-card" style="margin-top:16px">
        <div class="ad-card-head">
            <h2 class="ad-card-title">Order 6 bulan terakhir</h2>
            <span class="ad-mono" style="font-size:9.5px;letter-spacing:0.14em;text-transform:uppercase;color:#98a2b3">
                per bulan
            </span>
        </div>
        <div style="padding:14px 20px 20px">
            @php $maksBulan = (int) ($monthly->max() ?: 1); @endphp
            @forelse ($monthly as $ym => $total)
                @php
                    $label = \Carbon\Carbon::createFromFormat('Y-m', $ym)->translatedFormat('M Y');
                @endphp
                <div style="display:flex;align-items:center;gap:12px;padding:5px 0">
                    <span class="ad-mono" style="width:64px;font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:#98a2b3">{{ $label }}</span>
                    <span style="flex:1;height:5px;border-radius:999px;background:#eef1f6;overflow:hidden">
                        <span style="display:block;height:100%;border-radius:999px;background:linear-gradient(to right,#1d4ed8,#60a5fa);width:{{ max(4, round(($total / $maksBulan) * 100)) }}%"></span>
                    </span>
                    <span class="ad-mono" style="width:24px;text-align:right;font-size:11px;font-weight:700;color:#475467">{{ $total }}</span>
                </div>
            @empty
                <p style="font-size:12.5px;color:#98a2b3">Belum ada data.</p>
            @endforelse
        </div>
    </section>
@endsection
