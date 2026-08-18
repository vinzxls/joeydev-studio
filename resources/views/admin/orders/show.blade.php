@extends('layouts.admin')

@section('title', 'Order '.$order->order_code.' — Admin JoeyDev')

@section('content')
    <a href="{{ route('admin.orders.index', $order->trashed() ? ['arsip' => 1] : []) }}" class="text-sm font-bold text-slate-400 transition hover:text-slate-600">&larr; Kembali ke daftar order</a>

    @if ($order->trashed())
        <div class="mt-4 flex flex-col items-start justify-between gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 sm:flex-row sm:items-center">
            <p class="text-sm text-amber-800">
                <span class="font-bold">Order ini diarsipkan</span> {{ $order->deleted_at?->diffForHumans() }} —
                tidak tampil di daftar aktif dan tidak dihitung dalam statistik. Datanya masih utuh.
            </p>
            <form method="POST" action="{{ route('admin.orders.restore', $order->id) }}">
                @csrf
                <button class="whitespace-nowrap rounded-full bg-emerald-600 px-5 py-2 text-sm font-bold text-white shadow-md shadow-emerald-600/30 transition hover:bg-emerald-700">Pulihkan Order</button>
            </form>
        </div>
    @endif
    <div class="mt-3 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
            Order <span class="font-mono text-violet-600">{{ $order->order_code }}</span>
        </h1>
        <span class="w-fit rounded-full bg-slate-900 px-4 py-1.5 text-sm font-bold text-white">{{ $order->statusLabel() }}</span>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-extrabold text-slate-900">Kebutuhan Klien<span class="text-sky-500">.</span></h2>
                <p class="mt-3 font-bold text-slate-900">{{ $order->title }}</p>
                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $order->description }}</p>
                @if ($order->attachments->isNotEmpty())
                    <div class="mt-5">
                        <p class="text-sm font-bold text-slate-700">Lampiran</p>
                        <ul class="mt-2 space-y-1.5">
                            @foreach ($order->attachments as $file)
                                <li>
                                    <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank"
                                       class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-3 py-1.5 text-sm font-medium text-violet-600 transition hover:bg-violet-100">
                                        📎 {{ $file->original_name }} <span class="text-xs text-slate-400">({{ number_format($file->size / 1024, 0) }} KB)</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-extrabold text-slate-900">Update Status Progress<span class="text-violet-600">.</span></h2>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4 space-y-4">
                    @csrf @method('PATCH')
                    <div class="flex flex-wrap gap-2">
                        @foreach (\App\Models\Order::STATUSES as $key => $label)
                            <label class="cursor-pointer rounded-full border border-slate-200 px-4 py-2 text-sm font-bold text-slate-500 transition hover:border-violet-300 has-checked:border-violet-600 has-checked:bg-violet-600 has-checked:text-white has-checked:shadow-md has-checked:shadow-violet-600/30">
                                <input type="radio" name="status" value="{{ $key }}" class="hidden" @checked($order->status === $key)>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <textarea name="note" rows="2" maxlength="2000" placeholder="Catatan untuk klien (opsional) — tampil di halaman Cek Progress"
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100"></textarea>
                    <button class="rounded-full bg-violet-600 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/30 transition hover:bg-violet-700">Simpan Status</button>
                </form>

                <h3 class="mt-7 text-xs font-bold uppercase tracking-wide text-slate-400">Riwayat</h3>
                <ol class="mt-3 space-y-4 border-l-2 border-violet-100 pl-5">
                    @foreach ($order->statusLogs as $log)
                        <li class="relative">
                            <span class="absolute -left-[27px] top-1 h-3 w-3 rounded-full {{ $loop->first ? 'bg-violet-600 ring-4 ring-violet-100' : 'bg-slate-300' }}"></span>
                            <p class="text-sm font-bold text-slate-900">{{ $log->statusLabel() }}</p>
                            @if ($log->note)<p class="text-sm text-slate-600">{{ $log->note }}</p>@endif
                            <p class="mt-0.5 text-xs text-slate-400">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                        </li>
                    @endforeach
                </ol>
            </section>

            @if ($order->review)
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="font-extrabold text-slate-900">Review Klien<span class="text-amber-500">.</span></h2>
                    <p class="mt-3 text-xl text-amber-400">@for ($i = 1; $i <= 5; $i++){{ $i <= $order->review->rating ? '★' : '☆' }}@endfor</p>
                    @if ($order->review->comment)<p class="mt-1.5 text-sm text-slate-600">“{{ $order->review->comment }}”</p>@endif
                    <p class="mt-3 text-xs font-bold {{ $order->review->is_approved ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $order->review->is_approved ? '✓ Tampil di publik' : '⏳ Belum dimoderasi — atur di menu Review' }}
                    </p>
                </section>
            @endif
        </div>

        <div class="space-y-6">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-extrabold text-slate-900">Kontak Klien<span class="text-emerald-500">.</span></h2>
                <dl class="mt-4 space-y-2.5 text-sm">
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Nama</dt><dd class="font-bold text-slate-900">{{ $order->client_name }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">WhatsApp</dt>
                        <dd><a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->client_wa) }}" target="_blank" class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 transition hover:bg-emerald-200">{{ $order->client_wa }} ↗</a></dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Email</dt><dd class="font-bold text-slate-900">{{ $order->client_email }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Layanan</dt><dd class="text-right font-bold text-slate-900">{{ $order->subject->name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Deadline</dt><dd class="font-bold text-slate-900">{{ $order->deadline->format('d/m/Y') }}</dd></div>
                </dl>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-extrabold text-slate-900">Harga &amp; Pembayaran<span class="text-violet-600">.</span></h2>
                <dl class="mt-4 space-y-2 text-sm">
                    @if ($order->base_price !== null)
                        <div class="flex justify-between"><dt class="text-slate-500">Harga dasar</dt><dd class="font-bold">Rp{{ number_format($order->base_price, 0, ',', '.') }}</dd></div>
                        @foreach ([['scale_label', 'scale_value'], ['complexity_label', 'complexity_value'], ['urgency_label', 'urgency_value']] as [$l, $v])
                            @if ($order->$l)
                                <div class="flex justify-between text-slate-500"><dt>× {{ $order->$l }}</dt><dd>×{{ rtrim(rtrim(number_format($order->$v, 2), '0'), '.') }}</dd></div>
                            @endif
                        @endforeach
                        @foreach ($order->addons as $addon)
                            <div class="flex justify-between text-slate-500"><dt>+ {{ $addon->name }}</dt><dd>Rp{{ number_format($addon->price, 0, ',', '.') }}</dd></div>
                        @endforeach
                    @endif
                    <div class="flex items-center justify-between border-t border-dashed border-slate-200 pt-2.5">
                        <dt class="font-bold text-slate-900">Total</dt>
                        <dd class="text-lg font-extrabold text-violet-600">{{ $order->final_price !== null ? 'Rp'.number_format($order->final_price, 0, ',', '.') : 'Belum ditetapkan' }}</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('admin.orders.payment', $order) }}" class="mt-5 space-y-3.5 border-t border-slate-100 pt-5">
                    @csrf @method('PATCH')
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Status pembayaran</label>
                        <select name="payment_status" class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none">
                            @foreach (\App\Models\Order::PAYMENT_STATUSES as $key => $label)
                                <option value="{{ $key }}" @selected($order->payment_status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Harga final (Rp) — untuk order custom / hasil nego</label>
                        <input type="number" name="final_price" min="0" value="{{ $order->final_price }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Catatan internal admin</label>
                        <textarea name="admin_note" rows="2" maxlength="2000"
                                  class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">{{ $order->admin_note }}</textarea>
                    </div>
                    <button class="w-full rounded-full bg-slate-900 py-3 text-sm font-bold text-white transition hover:bg-slate-700">Simpan Pembayaran</button>
                </form>
            </section>

            @if ($order->trashed())
                <section class="rounded-3xl border border-rose-200 bg-rose-50/50 p-6">
                    <h2 class="font-extrabold text-rose-700">Hapus Permanen<span class="text-rose-400">.</span></h2>
                    <p class="mt-2 text-sm text-rose-700/80">
                        Menghapus order ini selamanya beserta riwayat status, add-on, review, dan file lampirannya.
                        Berbeda dengan arsip, yang ini <span class="font-bold">tidak bisa dipulihkan</span>.
                    </p>
                    <form method="POST" action="{{ route('admin.orders.force-destroy', $order->id) }}" class="mt-4 space-y-3"
                          onsubmit="return confirm('Hapus PERMANEN order {{ $order->order_code }} milik {{ $order->client_name }}?

Data tidak bisa dikembalikan lagi.')">
                        @csrf @method('DELETE')
                        <input type="hidden" name="confirm_code" value="{{ $order->order_code }}">
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-rose-500">
                                Ketik <span class="font-mono">{{ $order->order_code }}</span> untuk konfirmasi
                            </label>
                            <input type="text" name="order_code" required autocomplete="off" placeholder="{{ $order->order_code }}"
                                   class="w-full rounded-2xl border border-rose-300 bg-white px-4 py-2.5 font-mono text-sm transition focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-100">
                        </div>
                        <button class="w-full rounded-full bg-rose-600 py-3 text-sm font-bold text-white transition hover:bg-rose-700">
                            Hapus Permanen
                        </button>
                    </form>
                </section>
            @else
                <section class="rounded-3xl border border-amber-200 bg-amber-50/60 p-6">
                    <h2 class="font-extrabold text-amber-800">Arsipkan Order<span class="text-amber-500">.</span></h2>
                    <p class="mt-2 text-sm text-amber-800/80">
                        Order disembunyikan dari daftar aktif dan tidak lagi dihitung dalam statistik,
                        tapi datanya tetap tersimpan utuh dan <span class="font-bold">bisa dipulihkan kapan saja</span>.
                    </p>
                    <div class="mt-4 rounded-2xl bg-white/70 px-4 py-3 text-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-amber-600">Yang akan diarsipkan</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $order->client_name }}</p>
                        <p class="font-mono text-xs text-slate-500">{{ $order->order_code }} &middot; {{ $order->subject->name ?? '-' }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="mt-4 space-y-3"
                          onsubmit="return confirm('Arsipkan order {{ $order->order_code }} milik {{ $order->client_name }}?

Bisa dipulihkan lagi lewat tab Arsip.')">
                        @csrf @method('DELETE')
                        <input type="hidden" name="confirm_code" value="{{ $order->order_code }}">
                        <div>
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-amber-600">
                                Ketik <span class="font-mono">{{ $order->order_code }}</span> untuk konfirmasi
                            </label>
                            <input type="text" name="order_code" required autocomplete="off" placeholder="{{ $order->order_code }}"
                                   class="w-full rounded-2xl border border-amber-300 bg-white px-4 py-2.5 font-mono text-sm transition focus:border-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-100">
                        </div>
                        <button class="w-full rounded-full bg-amber-600 py-3 text-sm font-bold text-white transition hover:bg-amber-700">
                            Arsipkan Order
                        </button>
                    </form>
                </section>
            @endif
        </div>
    </div>
@endsection
