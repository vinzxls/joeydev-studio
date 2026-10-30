@extends('layouts.admin')

@section('title', 'Order — Admin JoeyDev')
@section('crumb', $arsip ? 'Arsip Order' : 'Order')

@section('top-actions')
    <a href="{{ route('admin.orders.index', $arsip ? [] : ['arsip' => 1]) }}" class="ad-btn-ghost">
        {{ $arsip ? 'Kembali ke aktif' : 'Lihat arsip' }}
    </a>
@endsection

@section('content')
    <div class="ad-page-head">
        <div>
            <p class="ad-eyebrow">{{ $arsip ? 'Terhapus lunak' : 'Operasional' }}</p>
            <h1 class="ad-title">{{ $arsip ? 'Arsip Order' : 'Order' }}<span>.</span></h1>
        </div>
        <p class="ad-mono text-[10px] uppercase tracking-[0.14em] text-[#98a2b3]">
            {{ $orders->total() }} baris
        </p>
    </div>

    {{-- Tab Aktif / Arsip: garis bawah, bukan pill penuh warna --}}
    <div class="mt-5 flex gap-6 border-b border-[#e4e7ec]">
        <a href="{{ route('admin.orders.index') }}"
           @class([
               '-mb-px border-b-2 pb-2.5 font-mono text-[11px] uppercase tracking-[0.12em] transition',
               'border-[#1d4ed8] text-[#101828]' => ! $arsip,
               'border-transparent text-[#98a2b3] hover:text-[#475467]' => $arsip,
           ])>
            Aktif
        </a>
        <a href="{{ route('admin.orders.index', ['arsip' => 1]) }}"
           @class([
               '-mb-px flex items-center gap-2 border-b-2 pb-2.5 font-mono text-[11px] uppercase tracking-[0.12em] transition',
               'border-[#1d4ed8] text-[#101828]' => $arsip,
               'border-transparent text-[#98a2b3] hover:text-[#475467]' => ! $arsip,
           ])>
            Arsip
            @if ($jumlahArsip > 0)
                <span class="ad-num rounded-sm bg-[#f4f6fb] px-1.5 text-[10px] font-bold text-[#475467]">{{ $jumlahArsip }}</span>
            @endif
        </a>
    </div>

    @if ($arsip)
        <p class="ad-note">
            <span>
                Order di sini tidak tampil di daftar aktif dan tidak dihitung dalam statistik,
                tapi datanya masih utuh dan bisa dipulihkan kapan saja.
            </span>
        </p>
    @endif

    {{-- Filter: satu baris kontrol, label mono kecil --}}
    <form method="GET" class="mt-4 flex flex-wrap items-end gap-3 rounded-lg border border-[#e4e7ec] bg-white p-4">
        @if ($arsip)
            <input type="hidden" name="arsip" value="1">
        @endif

        <div class="min-w-[180px] flex-1">
            <label class="ad-label" for="f-q">Cari</label>
            <input id="f-q" type="text" name="q" value="{{ request('q') }}"
                   placeholder="Kode / nama / judul" class="ad-input">
        </div>

        <div class="min-w-[150px]">
            <label class="ad-label" for="f-status">Status</label>
            <select id="f-status" name="status" class="ad-select">
                <option value="">Semua</option>
                @foreach (\App\Models\Order::STATUSES as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-[150px]">
            <label class="ad-label" for="f-subject">Subject</label>
            <select id="f-subject" name="subject" class="ad-select">
                <option value="">Semua</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(request('subject') == $subject->id)>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="ad-btn">Filter</button>

        @if (request()->hasAny(['q', 'status', 'subject']))
            <a href="{{ route('admin.orders.index', $arsip ? ['arsip' => 1] : []) }}"
               class="pb-2 font-mono text-[10px] uppercase tracking-[0.12em] text-[#98a2b3] transition-colors hover:text-[#b42318]">
                Reset
            </a>
        @endif
    </form>

    <div class="ad-card mt-4" style="overflow-x:auto">
        <table class="ad-tbl">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Klien</th>
                    <th>Judul</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Bayar</th>
                    <th style="text-align:right">Total</th>
                    @if ($arsip)
                        <th>Diarsipkan</th>
                        <th></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    @php
                        $telat = $order->deadline->isPast() && ! in_array($order->status, ['selesai', 'dibatalkan']);
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="ad-code">{{ $order->order_code }}</a>
                        </td>
                        <td>
                            <span class="ad-client">{{ $order->client_name }}</span>
                            <span class="ad-sub">{{ $order->subject->name ?? '—' }}</span>
                        </td>
                        <td style="max-width:190px">
                            <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#475467">{{ $order->title }}</span>
                        </td>
                        <td class="ad-mono" style="white-space:nowrap;font-size:11.5px;color:{{ $telat ? '#b42318' : '#667085' }};font-weight:{{ $telat ? '700' : '500' }}">
                            {{ $order->deadline->format('d/m/Y') }}
                            @if ($telat)
                                <span style="display:block;font-size:9px;letter-spacing:0.1em;text-transform:uppercase;color:#f04438">lewat</span>
                            @endif
                        </td>
                        <td><x-ad.chip :nilai="$order->status" /></td>
                        <td><x-ad.chip jenis="bayar" :nilai="$order->payment_status" /></td>
                        <td class="ad-money" style="text-align:right">
                            {{ $order->final_price !== null ? 'Rp'.number_format($order->final_price, 0, ',', '.') : '—' }}
                        </td>
                        @if ($arsip)
                            <td class="ad-mono" style="white-space:nowrap;font-size:10px;color:#98a2b3">
                                {{ $order->deleted_at?->diffForHumans() }}
                            </td>
                            <td style="text-align:right">
                                <form method="POST" action="{{ route('admin.orders.restore', $order->id) }}">
                                    @csrf
                                    <button class="ad-btn ad-btn-sm">Pulihkan</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $arsip ? 9 : 7 }}" style="padding:40px 20px;text-align:center;color:#98a2b3;font-size:12.5px">
                            {{ $arsip ? 'Tidak ada order di arsip.' : 'Tidak ada order yang cocok.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
