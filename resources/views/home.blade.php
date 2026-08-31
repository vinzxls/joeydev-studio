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
    <section class="bg-white py-16" id="tentang">
        <div class="mx-auto max-w-5xl px-4">
            <div class="grid items-start gap-10 lg:grid-cols-2">
                <div>
                    <x-jd.label-section path="/tentang">Tentang Saya</x-jd.label-section>
                    <h2 class="mt-5 font-display text-3xl font-extrabold leading-tight tracking-tight text-tinta sm:text-4xl">
                        SAYA <span class="text-aksen">JOEY FINTHEO</span> — PROGRAMMER<span class="text-aksen">.</span>
                    </h2>
                    <div class="mt-6 border-l border-aksen pl-4">
                        <p class="text-sm leading-relaxed text-redup sm:text-base">
                            Programmer di balik JoeyDev Studio. Saya membangun website, sistem informasi, dan aplikasi
                            yang fungsional dan rapi — dari kebutuhan sederhana sampai sistem yang kompleks.
                            Setiap project saya kerjakan langsung, jadi Anda selalu berkomunikasi dengan orang
                            yang benar-benar menulis kodenya.
                        </p>
                    </div>
                </div>

                <x-jd.panel-file path="~/joeydev">
                    <li class="flex items-center justify-between gap-4 px-5 py-3">
                        <span class="text-tinta">website/</span>
                        <span class="text-redup">laravel + tailwind</span>
                    </li>
                    <li class="flex items-center justify-between gap-4 px-5 py-3">
                        <span class="text-tinta">api/</span>
                        <span class="text-redup">rest + ajax</span>
                    </li>
                    <li class="flex items-center justify-between gap-4 px-5 py-3">
                        <span class="text-tinta">mobile/</span>
                        <span class="text-redup">flutter + dart</span>
                    </li>
                    <li class="flex items-center justify-between gap-4 px-5 py-3">
                        <span class="text-tinta">data/</span>
                        <span class="text-redup">python + mysql</span>
                    </li>
                </x-jd.panel-file>
            </div>
        </div>

        {{-- Pita teknologi berjalan --}}
        <div class="mt-12">
            <p class="text-center font-mono text-[11px] uppercase tracking-[0.18em] text-redup">Tech Stack</p>
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
                        <x-jd.chip-teknologi :nama="$nama" :warna="$warna" class="shrink-0" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan --}}
    <section class="mx-auto max-w-6xl px-4 py-16" id="layanan">
        <h2 class="text-center font-display text-3xl font-extrabold tracking-tight text-tinta sm:text-4xl">
            Layanan <span class="text-aksen">Kami</span><span class="text-aksen">.</span>
        </h2>
        <p class="mt-3 text-center text-redup">Harga dasar transparan — estimasi final menyesuaikan skala &amp; kompleksitas project Anda.</p>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($subjects as $subject)
                <a href="{{ route('order.create', ['subject' => $subject->id]) }}" class="block h-full">
                    <x-jd.kartu-layanan :nama="$subject->name" :deskripsi="$subject->description"
                                     :harga="$subject->base_price" :satuan="$subject->unit"
                                     :custom="$subject->is_custom || $subject->base_price === null" hover />
                </a>
            @endforeach
        </div>
    </section>

    {{-- Cara pesan --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-center font-display text-3xl font-extrabold tracking-tight text-tinta sm:text-4xl">
                Cara <span class="text-aksen">Pesan</span><span class="text-aksen">.</span>
            </h2>
            <div class="jd-muncul relative mt-10 grid gap-5 sm:grid-cols-4" data-jd-muncul>
                {{-- Garis penghubung yang terisi dari kiri ke kanan --}}
                <span class="jd-garis absolute left-[12%] right-[12%] top-[34px] hidden h-0.5 bg-garis sm:block"></span>

                @foreach ([
                    ['1', 'Pilih Layanan', 'Pilih kategori project yang Anda butuhkan.'],
                    ['2', 'Isi Kebutuhan', 'Jelaskan detail, deadline, dan lampiran pendukung.'],
                    ['3', 'Lihat Estimasi', 'Harga transparan langsung muncul, tanpa nego berbelit.'],
                    ['4', 'Pantau Progress', 'Track status pengerjaan lewat kode order Anda.'],
                ] as [$no, $title, $desc])
                    <div class="jd-item relative">
                        <x-jd.langkah-proses :nomor="$no" :judul="$title" :deskripsi="$desc" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimoni --}}
    @if ($reviews->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 py-16">
            <h2 class="text-center font-display text-3xl font-extrabold tracking-tight text-tinta sm:text-4xl">
                Kata <span class="text-aksen">Klien</span><span class="text-aksen">.</span>
            </h2>
            <div class="jd-pita mt-10" style="--jd-gap: 20px; --jd-durasi: 45s; --jd-fade: 6%;">
                <div class="jd-jalur" data-jd-gandakan>
                    @foreach ($reviews as $review)
                        <div class="w-[320px] shrink-0 sm:w-[340px]">
                            <x-jd.kartu-testimoni
                                :nama="mb_strtoupper(mb_substr($review->displayName(), 0, 1))"
                                :teks="($review->comment ?: '(tanpa komentar)')"
                                :rating="$review->rating" />
                        </div>
                    @endforeach
                </div>
            </div>
            <p class="mt-5 text-center text-xs text-redup">Arahkan kursor ke ulasan untuk berhenti dan membacanya</p>
        </section>
    @endif

    {{-- CTA bawah --}}
    <section class="mx-auto max-w-6xl px-4 pb-4 pt-2">
        <x-jd.panel-cta :judul="'Siap mulai project Anda?'" :pesan="route('order.create')">
            Estimasi harga langsung keluar — mulai dari
            <span class="font-bold text-kertas">Rp50.000</span>,
            tidak perlu tanya-tanya dulu.
        </x-jd.panel-cta>
    </section>
@endsection
