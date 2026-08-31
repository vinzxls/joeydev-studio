@extends('layouts.app')

@section('title', 'Pesan Layanan — JoeyDev Studio')

@section('content')
<div class="mx-auto max-w-6xl px-4 pb-10 pt-24 md:pt-32">
    <x-jd.label-section path="/pesan">Form Pemesanan</x-jd.label-section>
    <h1 class="mt-2 font-display text-3xl font-extrabold tracking-tight text-tinta sm:text-4xl">
        Pesan <span class="text-aksen">Layanan</span><span class="text-aksen">.</span>
    </h1>
    <p class="mt-2 text-redup">Isi detail kebutuhan Anda — estimasi harga muncul otomatis di panel ringkasan.</p>

    @if ($errors->any())
        <div class="mt-5 rounded-kartu border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
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
            <x-jd.kartu>
                <div class="p-6 sm:p-7">
                    <h2 class="flex items-center gap-3 font-extrabold text-tinta">
                        <span class="flex h-9 w-9 items-center justify-center rounded-kartu border border-garis bg-kertas text-sm font-extrabold text-tinta">1</span>
                        Pilih Layanan
                    </h2>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach ($subjects as $subject)
                            <label class="flex cursor-pointer items-start gap-3 rounded-kartu border border-garis p-4 transition hover:border-aksen has-checked:border-aksen has-checked:bg-aksen/5 has-checked:shadow-sm">
                                <input type="radio" name="subject_id" value="{{ $subject->id }}" class="mt-1 accent-aksen price-input"
                                       data-custom="{{ $subject->is_custom ? '1' : '0' }}"
                                       @checked(old('subject_id', $preselect) == $subject->id) required>
                                <span>
                                    <span class="block text-sm font-bold text-tinta">{{ $subject->name }}</span>
                                    <span class="block text-xs text-redup">
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
                </div>
            </x-jd.kartu>

            {{-- 2. Detail kebutuhan --}}
            <x-jd.kartu>
                <div class="p-6 sm:p-7">
                    <h2 class="flex items-center gap-3 font-extrabold text-tinta">
                        <span class="flex h-9 w-9 items-center justify-center rounded-kartu border border-garis bg-kertas text-sm font-extrabold text-tinta">2</span>
                        Detail Kebutuhan
                    </h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-tinta">Judul singkat <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required maxlength="150"
                                   placeholder="Contoh: Website company profile untuk toko saya"
                                   class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 text-sm transition focus:border-aksen focus:outline-none focus:ring-4 focus:ring-aksen/10">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-tinta">Deskripsi detail <span class="text-rose-500">*</span></label>
                            <textarea name="description" rows="5" required maxlength="10000"
                                      placeholder="Jelaskan kebutuhan Anda: fitur yang diinginkan, referensi, teknologi tertentu (jika ada), dll."
                                      class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 text-sm transition focus:border-aksen focus:outline-none focus:ring-4 focus:ring-aksen/10">{{ old('description') }}</textarea>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-bold text-tinta">Deadline yang diinginkan <span class="text-rose-500">*</span></label>
                                <input type="date" name="deadline" value="{{ old('deadline') }}" required min="{{ now()->toDateString() }}"
                                       class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 text-sm transition focus:border-aksen focus:outline-none focus:ring-4 focus:ring-aksen/10">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-bold text-tinta">Lampiran <span class="font-medium text-redup">(opsional, maks 5 file @ 10 MB)</span></label>
                                <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.zip"
                                       class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-garis file:px-4 file:py-1.5 file:text-sm file:font-bold file:text-tinta">
                                <p class="mt-1 text-xs text-redup">PDF, DOC/DOCX, gambar, atau ZIP.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-jd.kartu>

            {{-- 3. Faktor harga --}}
            <x-jd.kartu>
                <div class="p-6 sm:p-7" id="pricing-factors">
                    <h2 class="flex items-center gap-3 font-extrabold text-tinta">
                        <span class="flex h-9 w-9 items-center justify-center rounded-kartu border border-garis bg-kertas text-sm font-extrabold text-tinta">3</span>
                        Skala &amp; Kompleksitas
                    </h2>
                    <p class="mt-2 text-xs leading-relaxed text-redup">
                        Pilih sejujurnya sesuai kebutuhan Anda — ini menentukan estimasi harga.
                        Admin akan meninjau ulang dan mengonfirmasi harga final sebelum pengerjaan dimulai.
                    </p>
                    <p class="mt-2 text-xs text-redup" id="custom-note" hidden>Untuk layanan custom, bagian ini opsional — harga final akan dikonfirmasi admin setelah konsultasi.</p>

                    @foreach ([
                        ['name' => 'scale_id', 'label' => 'Skala pekerjaan', 'items' => $scales],
                        ['name' => 'complexity_id', 'label' => 'Kompleksitas teknis', 'items' => $complexities],
                        ['name' => 'urgency_id', 'label' => 'Urgensi deadline', 'items' => $urgencies],
                    ] as $group)
                        <div class="mt-5">
                            <p class="mb-2 text-sm font-bold text-tinta">{{ $group['label'] }} <span class="text-rose-500 required-star">*</span></p>
                            <div class="grid gap-2 sm:grid-cols-3">
                                @foreach ($group['items'] as $item)
                                    <label class="flex cursor-pointer items-start gap-2.5 rounded-kartu border border-garis p-3.5 transition hover:border-aksen has-checked:border-aksen has-checked:bg-aksen/5">
                                        <input type="radio" name="{{ $group['name'] }}" value="{{ $item->id }}"
                                               class="mt-0.5 accent-aksen price-input" @checked(old($group['name']) == $item->id)>
                                        <span>
                                            <span class="block text-sm font-bold text-tinta">{{ $item->label }} <span class="ml-0.5 rounded-full bg-garis px-1.5 py-0.5 text-[11px] font-bold text-redup">×{{ rtrim(rtrim(number_format($item->value, 2), '0'), '.') }}</span></span>
                                            <span class="block text-xs text-redup">{{ $item->description }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-7">
                        <p class="mb-2 text-sm font-bold text-tinta">Kebutuhan tambahan <span class="font-medium text-redup">(opsional)</span></p>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach ($addons as $addon)
                                <label class="flex cursor-pointer items-start gap-2.5 rounded-kartu border border-garis p-3.5 transition hover:border-aksen has-checked:border-aksen has-checked:bg-aksen/5">
                                    <input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}"
                                           class="mt-0.5 accent-aksen price-input"
                                           @checked(in_array($addon->id, old('addon_ids', [])))>
                                    <span>
                                        <span class="block text-sm font-bold text-tinta">{{ $addon->name }} <span class="text-xs font-bold text-aksen">+Rp{{ number_format($addon->price, 0, ',', '.') }}</span></span>
                                        <span class="block text-xs text-redup">{{ $addon->description }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-jd.kartu>

            {{-- 4. Kontak --}}
            <x-jd.kartu>
                <div class="p-6 sm:p-7">
                    <h2 class="flex items-center gap-3 font-extrabold text-tinta">
                        <span class="flex h-9 w-9 items-center justify-center rounded-kartu border border-garis bg-kertas text-sm font-extrabold text-tinta">4</span>
                        Data Kontak
                    </h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-tinta">Nama <span class="text-rose-500">*</span></label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}" required maxlength="100"
                                   class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 text-sm transition focus:border-aksen focus:outline-none focus:ring-4 focus:ring-aksen/10">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-tinta">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="tel" name="client_wa" value="{{ old('client_wa') }}" required maxlength="20" placeholder="08xxxxxxxxxx"
                                   class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 text-sm transition focus:border-aksen focus:outline-none focus:ring-4 focus:ring-aksen/10">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-tinta">Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="client_email" value="{{ old('client_email') }}" required maxlength="150"
                                   class="w-full rounded-kartu border border-garis bg-permukaan px-4 py-3 text-sm transition focus:border-aksen focus:outline-none focus:ring-4 focus:ring-aksen/10">
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-redup">Kode order &amp; update progress akan terhubung ke WA/email ini. Data kontak Anda tidak ditampilkan ke publik.</p>
                </div>
            </x-jd.kartu>
        </div>

        {{-- Panel ringkasan harga --}}
        <x-jd.kartu class="h-fit lg:sticky lg:top-24">
            <div class="p-6 sm:p-7">
                <h2 class="font-extrabold text-tinta">Ringkasan Estimasi<span class="text-aksen">.</span></h2>
                <div id="estimate-body" class="mt-4 space-y-2 text-sm text-redup">
                    <p class="text-redup">Pilih layanan untuk melihat estimasi harga.</p>
                </div>
                <div class="mt-5 rounded-kartu bg-tinta p-5 text-kertas">
                    <p class="text-xs font-semibold uppercase tracking-wider text-redup">Total Estimasi</p>
                    <p id="estimate-total" class="mt-1 text-3xl font-extrabold">—</p>
                    <p class="mt-2 text-xs leading-relaxed text-redup">
                        Estimasi awal berdasarkan pilihan Anda. Harga final dikonfirmasi admin
                        setelah meninjau detail kebutuhan, dan disepakati bersama sebelum pengerjaan dimulai.
                    </p>
                </div>
                <x-jd.tombol-utama class="mt-5 w-full" type="submit">
                    Kirim Order
                </x-jd.tombol-utama>
                <p class="mt-3 text-xs leading-relaxed text-redup">Dengan mengirim order, admin akan menghubungi Anda via WhatsApp untuk konfirmasi &amp; pembayaran DP.</p>
            </div>
        </x-jd.kartu>
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
                rows = '<p class="text-redup">Layanan custom — harga final dikonfirmasi admin setelah konsultasi singkat via WhatsApp.</p>';
                if (est.addons_total > 0) {
                    rows += `<div class="flex justify-between"><span>Add-on</span><span class="font-bold">${rupiah(est.addons_total)}</span></div>`;
                }
                total.textContent = 'Via konsultasi';
            } else {
                rows += `<div class="flex justify-between"><span>Harga dasar</span><span class="font-bold">${rupiah(est.base_price)}</span></div>`;
                for (const key of ['scale', 'complexity', 'urgency']) {
                    const m = est.breakdown[key];
                    if (m) rows += `<div class="flex justify-between text-redup"><span>× ${m.label}</span><span>×${parseFloat(m.value)}</span></div>`;
                }
                rows += `<div class="flex justify-between border-t border-dashed border-garis pt-2"><span>Subtotal</span><span class="font-bold">${rupiah(est.subtotal)}</span></div>`;
                for (const a of est.breakdown.addons) {
                    rows += `<div class="flex justify-between text-redup"><span>+ ${a.name}</span><span>${rupiah(a.price)}</span></div>`;
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
