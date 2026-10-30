@extends('layouts.admin')

@section('title', 'Order '.$order->order_code.' — Admin JoeyDev')
@section('crumb', $order->order_code)

@section('top-actions')
    <a href="{{ route('admin.orders.index', $order->trashed() ? ['arsip' => 1] : []) }}" class="ad-btn-ghost">
        &larr; Daftar order
    </a>
@endsection

@section('content')
    @if ($order->trashed())
        <div class="ad-note" style="margin-top:0">
            <span style="flex:1">
                <b>Order ini diarsipkan</b> {{ $order->deleted_at?->diffForHumans() }} — tidak tampil di daftar aktif
                dan tidak dihitung dalam statistik. Datanya masih utuh.
            </span>
            <form method="POST" action="{{ route('admin.orders.restore', $order->id) }}">
                @csrf
                <button class="ad-btn ad-btn-sm">Pulihkan</button>
            </form>
        </div>
    @endif

    <div class="ad-page-head">
        <div>
            <p class="ad-eyebrow">Order detail</p>
            <h1 class="ad-title">
                <span class="ad-mono" style="font-size:1.35rem;letter-spacing:-0.01em">{{ $order->order_code }}</span>
                <span>.</span>
            </h1>
            <p style="margin-top:8px;font-size:12.5px;color:#667085">
                Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }} &middot;
                {{ $order->subject->name ?? 'tanpa subject' }}
            </p>
        </div>
        <div style="display:flex;align-items:center;gap:7px">
            <x-ad.chip :nilai="$order->status" />
            <x-ad.chip jenis="bayar" :nilai="$order->payment_status" />
        </div>
    </div>

    <div class="ad-grid-2">

        {{-- ================= KOLOM KIRI ================= --}}
        <div class="ad-stack">

            {{-- Kebutuhan klien --}}
            <section class="ad-card">
                <div class="ad-card-head">
                    <h2 class="ad-card-title">Kebutuhan klien</h2>
                </div>
                <div style="padding:16px 20px 18px">
                    <p style="font-size:14px;font-weight:700;color:#101828">{{ $order->title }}</p>
                    <p style="margin-top:7px;font-size:12.5px;line-height:1.7;color:#475467;white-space:pre-line">{{ $order->description }}</p>

                    @if ($order->attachments->isNotEmpty())
                        <p class="ad-label" style="margin-top:18px;margin-bottom:8px">Lampiran</p>
                        <ul style="display:flex;flex-direction:column;gap:6px">
                            @foreach ($order->attachments as $file)
                                <li>
                                    <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" rel="noopener"
                                       style="display:inline-flex;align-items:center;gap:8px;border:1px solid #e4e7ec;border-radius:5px;background:#fafbfe;padding:6px 10px;font-size:12px;font-weight:600;color:#1d4ed8;text-decoration:none">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" style="width:12px;height:12px;flex:none" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                        </svg>
                                        {{ $file->original_name }}
                                        <span class="ad-mono" style="font-size:9.5px;color:#98a2b3;font-weight:500">
                                            {{ number_format($file->size / 1024, 0) }} KB
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>

            {{-- Update status --}}
            <section class="ad-card">
                <div class="ad-card-head">
                    <h2 class="ad-card-title">Update status progress</h2>
                    <span class="ad-mono" style="font-size:9.5px;letter-spacing:0.14em;text-transform:uppercase;color:#98a2b3">
                        tampil ke klien
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.orders.status', $order) }}" style="padding:16px 20px 18px">
                    @csrf @method('PATCH')

                    {{-- Radio chip: kotak kecil, bukan pill penuh --}}
                    <div style="display:flex;flex-wrap:wrap;gap:6px">
                        @foreach (\App\Models\Order::STATUSES as $key => $label)
                            <label style="cursor:pointer">
                                <input type="radio" name="status" value="{{ $key }}" class="peer sr-only" @checked($order->status === $key)>
                                <span style="display:inline-flex;border:1px solid {{ $order->status === $key ? '#1d4ed8' : '#e4e7ec' }};border-radius:4px;background:{{ $order->status === $key ? '#eff4ff' : '#fff' }};padding:5px 10px 6px;font-family:var(--font-mono);font-size:9.5px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:{{ $order->status === $key ? '#1d4ed8' : '#667085' }};transition:all 0.16s ease">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div style="margin-top:14px">
                        <label class="ad-label" for="note">Catatan untuk klien (opsional)</label>
                        <textarea id="note" name="note" rows="2" maxlength="2000" class="ad-textarea"
                                  placeholder="Tampil di halaman Cek Progress"></textarea>
                    </div>

                    <button class="ad-btn" style="margin-top:12px">Simpan status</button>
                </form>

                {{-- Riwayat --}}
                <div style="border-top:1px solid #eef1f6;padding-top:14px">
                    <p class="ad-label" style="padding:0 20px 4px">Riwayat</p>
                    <div class="ad-tl">
                        @forelse ($order->statusLogs as $log)
                            <div class="ad-tl-item {{ $loop->first ? 'on' : '' }}">
                                <span class="ad-tl-node"></span>
                                <p class="ad-tl-t">{{ $log->statusLabel() }}</p>
                                @if ($log->note)
                                    <p style="margin-top:3px;font-size:12px;line-height:1.6;color:#475467">{{ $log->note }}</p>
                                @endif
                                <p class="ad-tl-d">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @empty
                            <p style="padding:10px 0;font-size:12px;color:#98a2b3">Belum ada riwayat.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- Review klien --}}
            @if ($order->review)
                <section class="ad-card">
                    <div class="ad-card-head">
                        <h2 class="ad-card-title">Review klien</h2>
                        @if ($order->review->is_approved)
                            <span class="ad-chip selesai">Tampil</span>
                        @else
                            <span class="ad-chip revisi">Belum moderasi</span>
                        @endif
                    </div>
                    <div style="padding:14px 20px 18px">
                        <p style="font-size:14px;letter-spacing:0.08em;color:#f79009">
                            @for ($i = 1; $i <= 5; $i++){{ $i <= $order->review->rating ? '★' : '☆' }}@endfor
                        </p>
                        @if ($order->review->comment)
                            <p style="margin-top:6px;font-size:12.5px;line-height:1.7;color:#344054">
                                &ldquo;{{ $order->review->comment }}&rdquo;
                            </p>
                        @endif
                        @unless ($order->review->is_approved)
                            <p style="margin-top:10px;font-family:var(--font-mono);font-size:9.5px;letter-spacing:0.1em;text-transform:uppercase;color:#98a2b3">
                                Atur di menu
                                <a href="{{ route('admin.reviews.index') }}" style="color:#1d4ed8;text-decoration:none">Review</a>
                            </p>
                        @endunless
                    </div>
                </section>
            @endif
        </div>

        {{-- ================= KOLOM KANAN ================= --}}
        <div class="ad-stack">

            {{-- Kontak --}}
            <section class="ad-card">
                <div class="ad-card-head">
                    <h2 class="ad-card-title">Kontak klien</h2>
                </div>
                <div style="padding:6px 0 10px">
                    <div class="ad-sum"><span>Nama</span><b>{{ $order->client_name }}</b></div>
                    <div class="ad-sum">
                        <span>WhatsApp</span>
                        <b>
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->client_wa) }}" target="_blank" rel="noopener">
                                {{ $order->client_wa }}
                            </a>
                        </b>
                    </div>
                    <div class="ad-sum">
                        <span>Email</span>
                        <b style="font-weight:600;word-break:break-all;text-align:right">{{ $order->client_email }}</b>
                    </div>
                    <div class="ad-sum"><span>Layanan</span><b>{{ $order->subject->name ?? '—' }}</b></div>
                    <div class="ad-sum">
                        <span>Deadline</span>
                        <b>{{ $order->deadline->format('d/m/Y') }}</b>
                    </div>
                </div>
            </section>

            {{-- Harga & pembayaran --}}
            <section class="ad-card">
                <div class="ad-card-head">
                    <h2 class="ad-card-title">Harga &amp; pembayaran</h2>
                </div>

                <div style="padding:6px 0 4px">
                    @if ($order->base_price !== null)
                        <div class="ad-sum">
                            <span>Harga dasar</span>
                            <b>Rp{{ number_format($order->base_price, 0, ',', '.') }}</b>
                        </div>
                        @foreach ([['scale_label', 'scale_value'], ['complexity_label', 'complexity_value'], ['urgency_label', 'urgency_value']] as [$l, $v])
                            @if ($order->$l)
                                <div class="ad-sum" style="font-size:12px">
                                    <span>&times; {{ $order->$l }}</span>
                                    <b style="color:#667085;font-weight:600">&times;{{ rtrim(rtrim(number_format($order->$v, 2), '0'), '.') }}</b>
                                </div>
                            @endif
                        @endforeach
                        @foreach ($order->addons as $addon)
                            <div class="ad-sum" style="font-size:12px">
                                <span>+ {{ $addon->name }}</span>
                                <b style="color:#667085;font-weight:600">Rp{{ number_format($addon->price, 0, ',', '.') }}</b>
                            </div>
                        @endforeach
                    @endif

                    <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;margin:4px 20px 0;border-top:1px dashed #d0d5dd;padding-top:10px">
                        <span style="font-size:12px;font-weight:700;color:#101828">Total</span>
                        <span class="ad-num" style="font-family:var(--font-display);font-weight:800;font-size:1.15rem;letter-spacing:-0.02em;color:#1d4ed8">
                            {{ $order->final_price !== null ? 'Rp'.number_format($order->final_price, 0, ',', '.') : 'Belum ditetapkan' }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.orders.payment', $order) }}"
                      style="border-top:1px solid #eef1f6;margin-top:12px;padding:14px 20px 18px">
                    @csrf @method('PATCH')

                    <div>
                        <label class="ad-label" for="pay">Status pembayaran</label>
                        <select id="pay" name="payment_status" class="ad-select">
                            @foreach (\App\Models\Order::PAYMENT_STATUSES as $key => $label)
                                <option value="{{ $key }}" @selected($order->payment_status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-top:12px">
                        <label class="ad-label" for="final">Harga final (Rp) — untuk order custom / hasil nego</label>
                        <input id="final" type="number" name="final_price" min="0" value="{{ $order->final_price }}"
                               class="ad-input ad-num">
                    </div>

                    <div style="margin-top:12px">
                        <label class="ad-label" for="anote">Catatan internal admin</label>
                        <textarea id="anote" name="admin_note" rows="2" maxlength="2000" class="ad-textarea">{{ $order->admin_note }}</textarea>
                    </div>

                    <button class="ad-btn" style="margin-top:12px;width:100%">Simpan pembayaran</button>
                </form>
            </section>

            {{-- Arsip / hapus permanen --}}
            @if ($order->trashed())
                <section class="ad-card" style="border-color:#fecdca">
                    <div class="ad-card-head" style="border-color:#fee4e2">
                        <h2 class="ad-card-title" style="color:#b42318">Hapus permanen</h2>
                    </div>
                    <div style="padding:14px 20px 18px">
                        <p style="font-size:12px;line-height:1.65;color:#475467">
                            Menghapus order ini selamanya beserta riwayat status, add-on, review, dan file lampirannya.
                            Berbeda dengan arsip, yang ini <b>tidak bisa dipulihkan</b>.
                        </p>
                        <form method="POST" action="{{ route('admin.orders.force-destroy', $order->id) }}" style="margin-top:12px"
                              onsubmit="return confirm('Hapus PERMANEN order {{ $order->order_code }} milik {{ $order->client_name }}?

Data tidak bisa dikembalikan lagi.')">
                            @csrf @method('DELETE')
                            <input type="hidden" name="confirm_code" value="{{ $order->order_code }}">
                            <label class="ad-label" for="fcode" style="color:#b42318">
                                Ketik {{ $order->order_code }} untuk konfirmasi
                            </label>
                            <input id="fcode" type="text" name="order_code" required autocomplete="off"
                                   placeholder="{{ $order->order_code }}"
                                   class="ad-input ad-mono" style="border-color:#fecdca">
                            <button class="ad-btn ad-btn-danger" style="margin-top:10px;width:100%">Hapus permanen</button>
                        </form>
                    </div>
                </section>
            @else
                <section class="ad-card" style="border-color:#fedf89">
                    <div class="ad-card-head" style="border-color:#fef0c7">
                        <h2 class="ad-card-title" style="color:#b54708">Arsipkan order</h2>
                    </div>
                    <div style="padding:14px 20px 18px">
                        <p style="font-size:12px;line-height:1.65;color:#475467">
                            Order disembunyikan dari daftar aktif dan tidak lagi dihitung dalam statistik,
                            tapi datanya tetap tersimpan utuh dan <b>bisa dipulihkan kapan saja</b>.
                        </p>

                        <div style="margin-top:12px;border:1px solid #fef0c7;border-radius:6px;background:#fffaeb;padding:9px 12px">
                            <p class="ad-mono" style="font-size:9px;letter-spacing:0.12em;text-transform:uppercase;color:#b54708">Yang akan diarsipkan</p>
                            <p style="margin-top:3px;font-size:12.5px;font-weight:700;color:#101828">{{ $order->client_name }}</p>
                            <p class="ad-mono" style="font-size:10px;color:#667085;margin-top:2px">
                                {{ $order->order_code }} &middot; {{ $order->subject->name ?? '—' }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="margin-top:12px"
                              onsubmit="return confirm('Arsipkan order {{ $order->order_code }} milik {{ $order->client_name }}?

Bisa dipulihkan lagi lewat tab Arsip.')">
                            @csrf @method('DELETE')
                            <input type="hidden" name="confirm_code" value="{{ $order->order_code }}">
                            <label class="ad-label" for="acode" style="color:#b54708">
                                Ketik {{ $order->order_code }} untuk konfirmasi
                            </label>
                            <input id="acode" type="text" name="order_code" required autocomplete="off"
                                   placeholder="{{ $order->order_code }}"
                                   class="ad-input ad-mono" style="border-color:#fedf89">
                            <button class="ad-btn" style="margin-top:10px;width:100%;background:#b54708;border-color:#b54708">
                                Arsipkan order
                            </button>
                        </form>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
