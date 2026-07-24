@extends('layouts.admin')

@section('title', 'Order — Admin JoeyDev')

@section('content')
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Order<span class="text-violet-600">.</span></h1>

    <form method="GET" class="mt-5 flex flex-wrap items-end gap-3 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Cari</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Kode / nama / judul"
                   class="rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Status</label>
            <select name="status" class="rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none">
                <option value="">Semua</option>
                @foreach (\App\Models\Order::STATUSES as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Subject</label>
            <select name="subject" class="rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none">
                <option value="">Semua</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(request('subject') == $subject->id)>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded-full bg-violet-600 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/30 transition hover:bg-violet-700">Filter</button>
        @if (request()->hasAny(['q', 'status', 'subject']))
            <a href="{{ route('admin.orders.index') }}" class="py-2.5 text-sm font-bold text-slate-400 hover:text-slate-600">Reset</a>
        @endif
    </form>

    <div class="mt-5 overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-slate-100 text-xs font-bold uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="px-5 py-4">Kode</th>
                    <th class="px-5 py-4">Klien</th>
                    <th class="px-5 py-4">Judul</th>
                    <th class="px-5 py-4">Deadline</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Bayar</th>
                    <th class="px-5 py-4">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($orders as $order)
                    <tr class="transition hover:bg-violet-50/40">
                        <td class="px-5 py-3.5"><a href="{{ route('admin.orders.show', $order) }}" class="font-mono font-bold text-violet-600 hover:underline">{{ $order->order_code }}</a></td>
                        <td class="px-5 py-3.5">
                            <span class="font-semibold text-slate-700">{{ $order->client_name }}</span>
                            <span class="block text-xs text-slate-400">{{ $order->subject->name ?? '-' }}</span>
                        </td>
                        <td class="max-w-48 truncate px-5 py-3.5 text-slate-600">{{ $order->title }}</td>
                        <td class="whitespace-nowrap px-5 py-3.5 {{ $order->deadline->isPast() && ! in_array($order->status, ['selesai', 'dibatalkan']) ? 'font-bold text-rose-600' : 'text-slate-600' }}">
                            {{ $order->deadline->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5"><span class="whitespace-nowrap rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ $order->statusLabel() }}</span></td>
                        <td class="px-5 py-3.5"><span class="whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-bold {{ $order->payment_status === 'lunas' ? 'bg-emerald-100 text-emerald-700' : ($order->payment_status === 'dp' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500') }}">{{ $order->paymentStatusLabel() }}</span></td>
                        <td class="whitespace-nowrap px-5 py-3.5 font-bold text-slate-900">{{ $order->final_price !== null ? 'Rp'.number_format($order->final_price, 0, ',', '.') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">Tidak ada order yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $orders->links() }}</div>
@endsection
