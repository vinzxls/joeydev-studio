@extends('layouts.admin')

@section('title', 'Dashboard — Admin JoeyDev')

@section('content')
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Dashboard<span class="text-violet-600">.</span></h1>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Order Aktif', $stats['active_orders'], 'text-sky-600', 'bg-sky-100'],
            ['Order Bulan Ini', $stats['month_orders'], 'text-violet-600', 'bg-violet-100'],
            ['Pendapatan Bulan Ini', 'Rp'.number_format($stats['month_revenue'], 0, ',', '.'), 'text-emerald-600', 'bg-emerald-100'],
            ['Rata-rata Rating', $stats['avg_rating'] > 0 ? $stats['avg_rating'].' ★' : '—', 'text-amber-500', 'bg-amber-100'],
        ] as [$label, $value, $color, $chip])
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="inline-block rounded-full px-3 py-1 text-xs font-bold {{ $chip }} {{ $color }}">{{ $label }}</p>
                <p class="mt-3 text-2xl font-extrabold {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="font-extrabold text-slate-900">Order Terbaru<span class="text-violet-600">.</span></h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-violet-600 hover:underline">Lihat semua &rarr;</a>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 text-xs font-bold uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="py-2.5 pr-3">Kode</th>
                            <th class="py-2.5 pr-3">Klien</th>
                            <th class="py-2.5 pr-3">Layanan</th>
                            <th class="py-2.5 pr-3">Status</th>
                            <th class="py-2.5">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="py-3 pr-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-mono font-bold text-violet-600 hover:underline">{{ $order->order_code }}</a></td>
                                <td class="py-3 pr-3 font-semibold text-slate-700">{{ $order->client_name }}</td>
                                <td class="py-3 pr-3 text-slate-500">{{ $order->subject->name ?? '-' }}</td>
                                <td class="py-3 pr-3"><span class="whitespace-nowrap rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ $order->statusLabel() }}</span></td>
                                <td class="py-3 font-bold text-slate-900">{{ $order->final_price !== null ? 'Rp'.number_format($order->final_price, 0, ',', '.') : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-slate-400">Belum ada order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="space-y-6">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-extrabold text-slate-900">Subject Terpopuler<span class="text-pink-500">.</span></h2>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @forelse ($topSubjects as $row)
                        <li class="flex justify-between">
                            <span class="text-slate-600">{{ $row->subject->name ?? '-' }}</span>
                            <span class="rounded-full bg-violet-100 px-2.5 py-0.5 text-xs font-bold text-violet-600">{{ $row->total }} order</span>
                        </li>
                    @empty
                        <li class="text-slate-400">Belum ada data.</li>
                    @endforelse
                </ul>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-extrabold text-slate-900">6 Bulan Terakhir<span class="text-sky-500">.</span></h2>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @forelse ($monthly as $row)
                        <li class="flex items-center gap-2">
                            <span class="w-16 font-medium text-slate-500">{{ $row->ym }}</span>
                            <span class="h-2.5 rounded-full bg-gradient-to-r from-violet-500 to-indigo-400" style="width: {{ min(100, $row->total * 10) }}%"></span>
                            <span class="font-extrabold text-slate-900">{{ $row->total }}</span>
                        </li>
                    @empty
                        <li class="text-slate-400">Belum ada data.</li>
                    @endforelse
                </ul>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-extrabold text-slate-900">Ringkasan<span class="text-emerald-500">.</span></h2>
                <ul class="mt-4 space-y-2.5 text-sm text-slate-600">
                    <li class="flex justify-between"><span>Total order</span><span class="font-extrabold text-slate-900">{{ $stats['total_orders'] }}</span></li>
                    <li class="flex justify-between"><span>Order selesai</span><span class="font-extrabold text-slate-900">{{ $stats['completed_orders'] }}</span></li>
                    <li class="flex justify-between"><span>Total pendapatan (lunas)</span><span class="font-extrabold text-slate-900">Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</span></li>
                    <li class="flex justify-between"><span>Review menunggu moderasi</span>
                        <a href="{{ route('admin.reviews.index') }}" class="font-extrabold text-violet-600 hover:underline">{{ $stats['pending_reviews'] }}</a></li>
                </ul>
            </section>
        </div>
    </div>
@endsection
