@extends('layouts.app')

@section('title', 'Pesan Layanan — JoeyDev Studio')

@section('content')
<div class="mx-auto max-w-6xl px-4 pb-10 pt-24 md:pt-32">
    <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Form Pemesanan</p>
    <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
        Pesan <span class="bg-gradient-to-r from-violet-600 to-indigo-500 bg-clip-text text-transparent">Layanan</span><span class="text-violet-600">.</span>
    </h1>
    <p class="mt-2 text-slate-500">Isi detail kebutuhan Anda — estimasi harga muncul otomatis di panel ringkasan.</p>

    @if ($errors->any())
        <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
            <p class="font-bold">Periksa kembali isian Anda:</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('order.store') }}" enctype="multipart/form-data" id="order-form"
          class="mt-8 grid gap-6 lg:grid-cols-3">
        @csrf

        <div class="space-y-6 lg:col-span-2">
            {{-- 1. Layanan --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                <h2 class="flex items-center gap-3 font-extrabold text-slate-900">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-violet-100 text-sm font-extrabold text-violet-600">1</span>
                    Pilih Layanan
                </h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    @foreach ($subjects as $subject)
                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 transition hover:border-violet-300 has-checked:border-violet-500 has-checked:bg-violet-50 has-checked:shadow-md has-checked:shadow-violet-600/10">
                            <input type="radio" name="subject_id" value="{{ $subject->id }}" class="mt-1 accent-violet-600 price-input"
                                   data-custom="{{ $subject->is_custom ? '1' : '0' }}"
                                   @checked(old('subject_id', $preselect) == $subject->id) required>
                            <span>
                                <span class="block text-sm font-bold text-slate-900">{{ $subject->name }}</span>
                                <span class="block text-xs text-slate-500">
                                    @if ($subject->is_custom || $subject->base_price === null)
                                        Harga via konsultasi admin
                                    @else
                                        Mulai Rp{{ number_format($subject->base_price, 0, ',', '.') }} {{ $subject->unit }}
                                    @endif
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </section>

            {{-- 2. Detail kebutuhan --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                <h2 class="flex items-center gap-3 font-extrabold text-slate-900">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-sky-100 text-sm font-extrabold text-sky-600">2</span>
                    Detail Kebutuhan
                </h2>
                <div class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Judul singkat <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required maxlength="150"
                               placeholder="Contoh: Website company profile untuk toko saya"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Deskripsi detail <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="5" required maxlength="10000"
                                  placeholder="Jelaskan kebutuhan Anda: fitur yang diinginkan, referensi, teknologi tertentu (jika ada), dll."
                                  class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">{{ old('description') }}</textarea>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">Deadline yang diinginkan <span class="text-rose-500">*</span></label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}" required min="{{ now()->toDateString() }}"
                                   class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">Lampiran <span class="font-medium text-slate-400">(opsional, maks 5 file @ 10 MB)</span></label>
                            <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.zip"
                                   class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-violet-100 file:px-4 file:py-1.5 file:text-sm file:font-bold file:text-violet-600">
                            <p class="mt-1 text-xs text-slate-400">PDF, DOC/DOCX, gambar, atau ZIP.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 3. Faktor harga --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7" id="pricing-factors">
                <h2 class="flex items-center gap-3 font-extrabold text-slate-900">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-100 text-sm font-extrabold text-emerald-600">3</span>
                    Skala &amp; Kompleksitas
                </h2>
                <p class="mt-2 text-xs text-slate-400" id="custom-note" hidden>Untuk layanan custom, bagian ini opsional — harga final akan dikonfirmasi admin setelah konsultasi.</p>

                @foreach ([
                    ['name' => 'scale_id', 'label' => 'Skala pekerjaan', 'items' => $scales],
                    ['name' => 'complexity_id', 'label' => 'Kompleksitas teknis', 'items' => $complexities],
                    ['name' => 'urgency_id', 'label' => 'Urgensi deadline', 'items' => $urgencies],
                ] as $group)
                    <div class="mt-5">
                        <p class="mb-2 text-sm font-bold text-slate-700">{{ $group['label'] }} <span class="text-rose-500 required-star">*</span></p>
                        <div class="grid gap-2 sm:grid-cols-3">
                            @foreach ($group['items'] as $item)
                                <label class="flex cursor-pointer items-start gap-2.5 rounded-2xl border border-slate-200 p-3.5 transition hover:border-violet-300 has-checked:border-violet-500 has-checked:bg-violet-50">
                                    <input type="radio" name="{{ $group['name'] }}" value="{{ $item->id }}"
                                           class="mt-0.5 accent-violet-600 price-input" @checked(old($group['name']) == $item->id)>
                                    <span>
                                        <span class="block text-sm font-bold text-slate-900">{{ $item->label }} <span class="ml-0.5 rounded-full bg-slate-100 px-1.5 py-0.5 text-[11px] font-bold text-slate-500">×{{ rtrim(rtrim(number_format($item->value, 2), '0'), '.') }}</span></span>
                                        <span class="block text-xs text-slate-500">{{ $item->description }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="mt-7">
                    <p class="mb-2 text-sm font-bold text-slate-700">Kebutuhan tambahan <span class="font-medium text-slate-400">(opsional)</span></p>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach ($addons as $addon)
                            <label class="flex cursor-pointer items-start gap-2.5 rounded-2xl border border-slate-200 p-3.5 transition hover:border-violet-300 has-checked:border-violet-500 has-checked:bg-violet-50">
                                <input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}"
                                       class="mt-0.5 accent-violet-600 price-input"
                                       @checked(in_array($addon->id, old('addon_ids', [])))>
                                <span>
                                    <span class="block text-sm font-bold text-slate-900">{{ $addon->name }} <span class="text-xs font-bold text-violet-600">+Rp{{ number_format($addon->price, 0, ',', '.') }}</span></span>
                                    <span class="block text-xs text-slate-500">{{ $addon->description }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- 4. Kontak --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                <h2 class="flex items-center gap-3 font-extrabold text-slate-900">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-amber-100 text-sm font-extrabold text-amber-600">4</span>
                    Data Kontak
                </h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Nama <span class="text-rose-500">*</span></label>
                        <input type="text" name="client_name" value="{{ old('client_name') }}" required maxlength="100"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="tel" name="client_wa" value="{{ old('client_wa') }}" required maxlength="20" placeholder="08xxxxxxxxxx"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-slate-700">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="client_email" value="{{ old('client_email') }}" required maxlength="150"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Kode order &amp; update progress akan terhubung ke WA/email ini. Data kontak Anda tidak ditampilkan ke publik.</p>
            </section>
        </div>

        {{-- Panel ringkasan harga --}}
        <aside class="h-fit rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7 lg:sticky lg:top-24">
            <h2 class="font-extrabold text-slate-900">Ringkasan Estimasi<span class="text-violet-600">.</span></h2>
            <div id="estimate-body" class="mt-4 space-y-2 text-sm text-slate-600">
                <p class="text-slate-400">Pilih layanan untuk melihat estimasi harga.</p>
            </div>
            <div class="mt-5 rounded-2xl bg-slate-900 p-5 text-white">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Estimasi</p>
                <p id="estimate-total" class="mt-1 text-3xl font-extrabold">—</p>
            </div>
            <button type="submit"
                    class="mt-5 flex w-full items-center justify-center gap-2 rounded-full bg-violet-600 py-3.5 font-bold text-white shadow-lg shadow-violet-600/30 transition hover:-translate-y-0.5 hover:bg-violet-700">
                Kirim Order
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </button>
            <p class="mt-3 text-xs leading-relaxed text-slate-400">Dengan mengirim order, admin akan menghubungi Anda via WhatsApp untuk konfirmasi &amp; pembayaran DP.</p>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('order-form');
    const body = document.getElementById('estimate-body');
    const total = document.getElementById('estimate-total');
    const customNote = document.getElementById('custom-note');
    const rupiah = (n) => 'Rp' + new Intl.NumberFormat('id-ID').format(n);
    let timer = null;

    async function refresh() {
        const data = new FormData(form);
        const subjectId = data.get('subject_id');
        if (!subjectId) return;

        const selected = form.querySelector('input[name="subject_id"]:checked');
        const isCustom = selected && selected.dataset.custom === '1';
        customNote.hidden = !isCustom;
        document.querySelectorAll('.required-star').forEach(el => el.style.visibility = isCustom ? 'hidden' : 'visible');

        const payload = {
            subject_id: subjectId,
            scale_id: data.get('scale_id'),
            complexity_id: data.get('complexity_id'),
            urgency_id: data.get('urgency_id'),
            addon_ids: data.getAll('addon_ids[]'),
        };

        try {
            const res = await fetch('{{ route('order.estimate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            });
            if (!res.ok) return;
            const est = await res.json();

            let rows = '';
            if (est.is_custom) {
                rows = '<p class="text-slate-500">Layanan custom — harga final dikonfirmasi admin setelah konsultasi singkat via WhatsApp.</p>';
                if (est.addons_total > 0) {
                    rows += `<div class="flex justify-between"><span>Add-on</span><span class="font-bold">${rupiah(est.addons_total)}</span></div>`;
                }
                total.textContent = 'Via konsultasi';
            } else {
                rows += `<div class="flex justify-between"><span>Harga dasar</span><span class="font-bold">${rupiah(est.base_price)}</span></div>`;
                for (const key of ['scale', 'complexity', 'urgency']) {
                    const m = est.breakdown[key];
                    if (m) rows += `<div class="flex justify-between text-slate-500"><span>× ${m.label}</span><span>×${parseFloat(m.value)}</span></div>`;
                }
                rows += `<div class="flex justify-between border-t border-dashed border-slate-200 pt-2"><span>Subtotal</span><span class="font-bold">${rupiah(est.subtotal)}</span></div>`;
                for (const a of est.breakdown.addons) {
                    rows += `<div class="flex justify-between text-slate-500"><span>+ ${a.name}</span><span>${rupiah(a.price)}</span></div>`;
                }
                total.textContent = rupiah(est.final_price);
            }
            body.innerHTML = rows;
        } catch (e) { /* abaikan error jaringan sementara */ }
    }

    form.querySelectorAll('.price-input').forEach(el => {
        el.addEventListener('change', () => {
            clearTimeout(timer);
            timer = setTimeout(refresh, 150);
        });
    });

    if (form.querySelector('input[name="subject_id"]:checked')) refresh();
})();
</script>
@endpush
