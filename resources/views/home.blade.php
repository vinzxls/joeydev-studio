@extends('layouts.app')

@section('content')
    {{-- Hero — BLUEPRINT.md §6 (02). Grid 7/5, headline display, kartu profil,
         statistik dinamis dari database. Dekorasi konstelasi & backdrop miring
         versi lama dihapus: itu sumber overflow horizontal 5px di mobile. --}}
    @php $waHero = preg_replace('/\D/', '', (string) config('contact.whatsapp')); @endphp

    <section data-jd-hero class="jd-grid relative border-b border-garis">
        <div class="mx-auto max-w-[1280px] px-4 pb-20 pt-14 md:px-8 md:pb-28 md:pt-20">
            <div class="grid items-center gap-14 lg:grid-cols-12 lg:gap-12">

                {{-- Kiri — 7 kolom --}}
                <div class="lg:col-span-7">
                    <x-jd.label-section path="/beranda">Jasa software &amp; project IT</x-jd.label-section>

                    <h1 class="jd-judul mt-6 text-[clamp(2.5rem,8.5vw,5.25rem)] text-tinta">
                        Saya bangun<br>
                        sistem yang<br>
                        <span class="text-aksen">dipakai.</span>
                    </h1>

                    <p class="mt-7 max-w-xl text-base leading-relaxed text-redup md:text-lg">
                        Website, aplikasi mobile, machine learning, sampai IoT. Estimasi harga
                        langsung keluar saat Anda mengisi form — dan progressnya bisa dipantau
                        kapan saja lewat kode order.
                    </p>

                    <div class="mt-9 flex flex-col items-start gap-5 sm:flex-row sm:items-center sm:gap-7">
                        <x-jd.tombol-utama :href="route('order.create')" class="w-full justify-center sm:w-auto">
                            Pesan Sekarang
                        </x-jd.tombol-utama>

                        {{-- Bobotnya sengaja di bawah tombol utama: ini jalur klien lama,
                             bukan ajakan untuk pengunjung baru. --}}
                        <a href="{{ route('track.form') }}"
                           class="group inline-flex items-center gap-2 text-sm font-semibold text-redup underline-offset-4 transition-colors hover:text-tinta hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen">
                            Sudah pesan? Cek progress
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                 class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:translate-x-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h15m0 0-5.5-5.5M19 12l-5.5 5.5" />
                            </svg>
                        </a>
                    </div>

                    {{-- Statistik — angka ditarik dari database, jangan pernah ditulis tetap --}}
                    <dl class="mt-14 grid max-w-lg grid-cols-3 gap-px border border-garis bg-garis">
                        @foreach ([
                            [$stats['completed'], 'Project selesai'],
                            [$stats['avg_rating'] > 0 ? number_format($stats['avg_rating'], 1).'★' : '—', 'Rata-rata rating'],
                            [$stats['review_count'], 'Ulasan klien'],
                        ] as [$nilai, $label])
                            <div class="bg-kertas px-4 py-5">
                                <dt class="sr-only">{{ $label }}</dt>
                                <dd>
                                    <span class="jd-judul block text-3xl text-tinta md:text-4xl">{{ $nilai }}</span>
                                    <span class="mt-2 block font-mono text-[10px] uppercase leading-tight tracking-[0.14em] text-redup">{{ $label }}</span>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                {{-- Kanan — 5 kolom, kartu identitas --}}
                <div class="lg:col-span-5">
                    <x-jd.kartu kurung class="mx-auto max-w-sm p-3 lg:max-w-none">
                        <div class="overflow-hidden rounded-kartu border border-garis">
                            <img src="{{ asset('images/profil-joey.jpg') }}" alt="Joey Fintheo"
                                 width="640" height="800" loading="eager"
                                 class="aspect-[4/5] w-full object-cover grayscale transition-[filter,transform] duration-300 hover:scale-[1.03] hover:grayscale-0">
                        </div>

                        <div class="px-3 pb-2 pt-5">
                            <p class="jd-judul text-2xl leading-none text-tinta">Joey Fintheo</p>
                            <p class="mt-2 font-mono text-[11px] uppercase tracking-[0.14em] text-redup">
                                Programmer &middot; JoeyDev Studio
                            </p>

                            <div class="mt-5 border-t border-garis pt-4">
                                <x-jd.badge-status>Menerima project baru</x-jd.badge-status>
                            </div>

                            @if ($waHero !== '')
                                <a href="https://wa.me/{{ $waHero }}?text={{ rawurlencode((string) config('contact.whatsapp_message')) }}"
                                   target="_blank" rel="noopener"
                                   class="group mt-4 flex items-center justify-between gap-2 rounded-kartu border border-garis px-4 py-3 font-mono text-[11px] uppercase tracking-[0.14em] text-redup transition-colors hover:border-tinta hover:text-tinta focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen">
                                    Konsultasi via WhatsApp
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:translate-x-0.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </x-jd.kartu>
                </div>
            </div>
        </div>
    </section>

    {{-- Tentang Saya --}}
    {{-- overflow-hidden: backdrop miring di bawah foto digeser translate-x-3 dan
         menembus tepi kanan viewport di mobile. Section ini dibangun ulang di
         Fase 6; sampai saat itu dekorasinya cukup dipotong di batas section. --}}
    <section class="overflow-hidden bg-white py-16" id="tentang">
        <div class="mx-auto grid max-w-5xl items-center gap-10 px-4 md:grid-cols-2 md:gap-14">
            <div class="relative mx-auto w-full max-w-sm">
                {{-- Backdrop miring ala referensi --}}
                <span class="absolute inset-0 translate-x-3 translate-y-3 rotate-3 rounded-[2rem] bg-gradient-to-br from-violet-500 to-indigo-400"></span>
                <img src="{{ asset('images/profil-joey.jpg') }}" alt="Joey Fintheo"
                     class="relative w-full rounded-[2rem] border-4 border-white object-cover shadow-xl shadow-slate-900/10">
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Tentang Saya</p>
                <h2 class="mt-4 text-3xl font-extrabold leading-snug tracking-tight text-slate-900 sm:text-4xl">
                    SAYA
                    <span class="mt-2 block"><span class="inline-block -rotate-1 rounded-2xl bg-sky-500 px-4 py-1 text-white shadow-md shadow-sky-500/30">JOEY FINTHEO</span></span>
                    <span class="mt-3 block"><span class="inline-block rotate-1 rounded-2xl bg-emerald-500 px-4 py-1 text-white shadow-md shadow-emerald-500/30">PROGRAMMER</span><span class="text-emerald-500">.</span></span>
                </h2>
                <div class="mt-6 border-l-4 border-violet-500 pl-4">
                    <p class="text-sm leading-relaxed text-slate-600 sm:text-base">
                        Programmer di balik JoeyDev Studio. Saya membangun website, sistem informasi, dan aplikasi
                        yang fungsional dan rapi — dari kebutuhan sederhana sampai sistem yang kompleks.
                        Setiap project saya kerjakan langsung, jadi Anda selalu berkomunikasi dengan orang
                        yang benar-benar menulis kodenya.
                    </p>
                </div>
            </div>
        </div>

        {{-- Pita teknologi berjalan --}}
        <div class="mt-12">
            <p class="text-center text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Tech Stack</p>
            <div class="jd-pita mt-5" style="--jd-gap: 14px; --jd-durasi: 32s; --jd-fade: 12%;">
                <div class="jd-jalur" data-jd-gandakan>
                    @foreach ([
                        ['Laravel', '#ef4444'],
                        ['PHP', '#6366f1'],
                        ['MySQL', '#0ea5e9'],
                        ['Tailwind CSS', '#06b6d4'],
                        ['JavaScript', '#f59e0b'],
                        ['Python', '#3b82f6'],
                        ['Flutter', '#38bdf8'],
                        ['Git', '#f97316'],
                    ] as [$nama, $warna])
                        <span class="flex shrink-0 items-center gap-2.5 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $warna }}"></span>
                            {{ $nama }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan --}}
    <section class="mx-auto max-w-6xl px-4 py-16" id="layanan">
        <h2 class="text-center text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
            Layanan <span class="bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text text-transparent">Kami</span><span class="text-pink-500">.</span>
        </h2>
        <p class="mt-3 text-center text-slate-500">Harga dasar transparan — estimasi final menyesuaikan skala &amp; kompleksitas project Anda.</p>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($subjects as $subject)
                <a href="{{ route('order.create', ['subject' => $subject->id]) }}"
                   class="group flex flex-col rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1.5 hover:border-violet-300 hover:shadow-xl hover:shadow-violet-600/10">
                    <h3 class="font-bold text-slate-900 transition group-hover:text-violet-600">{{ $subject->name }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">{{ $subject->description }}</p>
                    <p class="mt-5">
                        @if ($subject->is_custom || $subject->base_price === null)
                            <span class="inline-block rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">Harga via konsultasi</span>
                        @else
                            <span class="inline-block rounded-full bg-violet-100 px-3 py-1 text-xs font-bold text-violet-700">Mulai Rp{{ number_format($subject->base_price, 0, ',', '.') }} <span class="font-medium text-violet-400">{{ $subject->unit }}</span></span>
                        @endif
                    </p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Cara pesan --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-center text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Cara <span class="bg-gradient-to-r from-emerald-500 to-teal-500 bg-clip-text text-transparent">Pesan</span><span class="text-emerald-500">.</span>
            </h2>
            <div class="jd-muncul relative mt-10 grid gap-5 sm:grid-cols-4" data-jd-muncul>
                {{-- Garis penghubung yang terisi dari kiri ke kanan --}}
                <span class="jd-garis absolute left-[12%] right-[12%] top-[34px] hidden h-0.5 bg-slate-200 sm:block"></span>

                @foreach ([
                    ['1', 'Pilih Layanan', 'Pilih kategori project yang Anda butuhkan.', 'bg-violet-100 text-violet-600'],
                    ['2', 'Isi Kebutuhan', 'Jelaskan detail, deadline, dan lampiran pendukung.', 'bg-sky-100 text-sky-600'],
                    ['3', 'Lihat Estimasi', 'Harga transparan langsung muncul, tanpa nego berbelit.', 'bg-emerald-100 text-emerald-600'],
                    ['4', 'Pantau Progress', 'Track status pengerjaan lewat kode order Anda.', 'bg-amber-100 text-amber-600'],
                ] as [$no, $title, $desc, $badge])
                    <div class="jd-item group relative rounded-3xl border border-slate-200 bg-[#f7f8fc] p-6 text-center transition hover:border-violet-300 hover:shadow-xl hover:shadow-violet-600/10">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl text-xl font-extrabold transition duration-300 group-hover:-rotate-6 group-hover:scale-110 {{ $badge }}">{{ $no }}</span>
                        <h3 class="mt-4 font-bold text-slate-900">{{ $title }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimoni --}}
    @if ($reviews->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 py-16">
            <h2 class="text-center text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Kata <span class="bg-gradient-to-r from-sky-500 to-indigo-500 bg-clip-text text-transparent">Klien</span><span class="text-sky-500">.</span>
            </h2>
            <div class="jd-pita mt-10" style="--jd-gap: 20px; --jd-durasi: 45s; --jd-fade: 6%;">
                <div class="jd-jalur" data-jd-gandakan>
                    @foreach ($reviews as $review)
                        <figure class="w-[320px] shrink-0 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-violet-300 hover:shadow-xl hover:shadow-violet-600/10 sm:w-[340px]">
                            <p class="text-lg text-amber-400">
                                @for ($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                            </p>
                            @if ($review->comment)
                                <blockquote class="mt-3 min-h-11 text-sm leading-relaxed text-slate-600">“{{ $review->comment }}”</blockquote>
                            @else
                                <p class="mt-3 min-h-11 text-sm italic text-slate-400">(tanpa komentar)</p>
                            @endif
                            <figcaption class="mt-4 flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-violet-100 text-sm font-extrabold text-violet-600">{{ mb_strtoupper(mb_substr($review->displayName(), 0, 1)) }}</span>
                                <span>
                                    <span class="block text-sm font-bold text-slate-900">{{ $review->displayName() }}</span>
                                    <span class="block text-xs text-slate-400">{{ $review->order->subject->name ?? '' }}</span>
                                </span>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
            <p class="mt-5 text-center text-xs text-slate-400">Arahkan kursor ke ulasan untuk berhenti dan membacanya</p>
        </section>
    @endif

    {{-- CTA bawah --}}
    <section class="mx-auto max-w-6xl px-4 pb-4 pt-2">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 px-6 py-14 text-center text-white">
            <span class="absolute -left-6 -top-6 h-24 w-24 rotate-12 rounded-3xl bg-violet-500/40"></span>
            <span class="absolute -bottom-8 right-10 h-28 w-28 -rotate-12 rounded-3xl bg-sky-500/30"></span>
            <span class="absolute right-24 top-6 h-10 w-10 rotate-45 rounded-xl bg-pink-500/40"></span>
            <h2 class="relative text-3xl font-extrabold tracking-tight sm:text-4xl">
                Siap mulai project Anda<span class="text-violet-400">?</span>
            </h2>
            <p class="relative mt-3 text-slate-300">Estimasi harga langsung keluar — mulai dari <span class="rounded-lg bg-violet-500/30 px-2 py-0.5 font-bold text-violet-200">Rp50.000</span>, tidak perlu tanya-tanya dulu.</p>
            <a href="{{ route('order.create') }}"
               class="relative mt-8 inline-flex items-center gap-2 rounded-full bg-white px-8 py-3.5 font-bold text-slate-900 shadow-lg transition hover:-translate-y-0.5 hover:bg-violet-50">
                Pesan Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </a>
        </div>
    </section>
@endsection
