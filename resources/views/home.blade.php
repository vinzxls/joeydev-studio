@extends('layouts.app')

@section('title', 'JoeyDev Studio — Jasa Pembuatan Software & Project IT')

@section('content')
    @php
        // Nomor WhatsApp untuk kartu kontak (hanya digit).
        $waHero = preg_replace('/\D/', '', (string) config('contact.whatsapp'));

        // Tech stack keycaps: [nama, glyph, warna merek].
        $keycaps = [
            ['Laravel', 'L', '#ff2d20'],
            ['PHP', 'PHP', '#7a86f5'],
            ['MySQL', 'SQL', '#3ba7e0'],
            ['Tailwind', 'TW', '#22d3ee'],
            ['JavaScript', 'JS', '#f7df1e'],
            ['Python', 'PY', '#4b9cff'],
            ['Flutter', 'FT', '#45c8f7'],
            ['Git', 'GIT', '#f97316'],
        ];

        $langkah = [
            ['01', 'Pilih Layanan', 'Tentukan kategori project yang paling mendekati kebutuhan Anda.'],
            ['02', 'Isi Kebutuhan', 'Jelaskan detail, deadline, dan lampirkan file pendukung lewat form yang sama.'],
            ['03', 'Lihat Estimasi', 'Harga muncul langsung di layar setelah form terisi — transparan, tanpa nego berbelit.'],
            ['04', 'Pantau Progress', 'Lacak status pengerjaan kapan saja lewat kode order yang Anda terima.'],
        ];

        $kataKunci = ['Website', 'Aplikasi Mobile', 'Sistem Informasi', 'IoT', 'Machine Learning', 'Design UI/UX'];
    @endphp

    {{-- ================= 1. HERO =================
         Hanya foto + nama sebagai watermark raksasa. Teks dan tombol
         sengaja diletakkan di section berikutnya (BLUEPRINT §6). --}}
    <section class="relative flex min-h-[88vh] items-center justify-center overflow-hidden px-4" data-jd-hero>
        <div class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
            <p class="jd-watermark">JOEY<br>FINTHEO</p>
        </div>

        <div class="relative z-10 flex justify-center pb-8 pt-10">
            <div class="jd-portrait-frame">
                <img src="{{ asset('images/profil-joey.jpg') }}"
                     alt="Joey Fintheo"
                     width="640" height="800" loading="eager"
                     class="jd-portrait rounded-2xl">
            </div>
        </div>
    </section>

    {{-- ================= 2. PITA KATA KUNCI ================= --}}
    <section class="jd-strip" aria-hidden="true">
        <div class="jd-strip-track" id="jd-strip">
            <div class="jd-strip-group" id="jd-strip-group">
                @foreach ($kataKunci as $i => $kata)
                    <span class="jd-strip-word {{ $i % 2 === 0 ? 'jd-strip-word--solid' : 'jd-strip-word--hollow' }}">{{ $kata }}</span>
                    <span class="jd-strip-dot"></span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= 3. PERKENALAN ================= --}}
    <section class="border-t border-garis px-4 py-24 md:py-28">
        <div class="mx-auto grid max-w-6xl items-start gap-14 lg:grid-cols-12">

            <div class="lg:col-span-7">
                <div class="jd-reveal flex flex-wrap items-center gap-4">
                    <p class="jd-eyebrow">Programmer &middot; JoeyDev Studio</p>
                    <span class="jd-available"><i></i> Menerima project</span>
                </div>

                <h1 class="jd-title jd-reveal mt-6 text-[clamp(2.75rem,7vw,5rem)]" style="--d: .06s">
                    JOEY <span class="text-aksen-terang">DEV</span>
                </h1>

                <p class="jd-reveal mt-7 max-w-xl text-[15px] leading-relaxed text-[#93a3bf] md:text-base" style="--d: .12s">
                    Saya membangun website, aplikasi mobile, dan sistem IT yang benar-benar dipakai.
                    Dikerjakan langsung oleh yang menulis kodenya — tanpa perantara, tanpa estimasi kabur.
                </p>

                <div class="jd-reveal mt-10 flex flex-col items-stretch gap-3 sm:flex-row sm:items-center" style="--d: .18s">
                    <a href="{{ route('order.create') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-aksen-terang px-7 py-3.5 text-sm font-semibold text-kertas transition hover:-translate-y-0.5 hover:bg-[#60a5fa] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen-terang focus-visible:ring-offset-2 focus-visible:ring-offset-kertas">
                        Pesan Sekarang
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h15m0 0-5.5-5.5M19 12l-5.5 5.5"/>
                        </svg>
                    </a>
                    <a href="#layanan"
                       class="inline-flex items-center justify-center rounded-xl border border-[#24314d] bg-permukaan px-7 py-3.5 text-sm font-semibold text-[#c8d6ef] transition hover:border-aksen-terang hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen-terang">
                        Lihat Layanan
                    </a>
                </div>
            </div>

            <div class="jd-reveal lg:col-span-5" style="--d: .1s">
                {{-- Angka ditarik dari database, jangan ditulis tetap. --}}
                <div class="jd-spec">
                    <div class="jd-spec-row">
                        <span class="jd-spec-k">Project selesai</span>
                        <span class="jd-spec-v">{{ str_pad((string) $stats['completed'], 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="jd-spec-row">
                        <span class="jd-spec-k">Rata-rata rating</span>
                        <span class="jd-spec-v">
                            @if ($stats['avg_rating'] > 0)
                                {{ number_format($stats['avg_rating'], 1, ',', '.') }} <small>&#9733;</small>
                            @else
                                &mdash;
                            @endif
                        </span>
                    </div>
                    <div class="jd-spec-row">
                        <span class="jd-spec-k">Ulasan klien</span>
                        <span class="jd-spec-v">{{ str_pad((string) $stats['review_count'], 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="jd-spec-row">
                        <span class="jd-spec-k">Berbasis di</span>
                        <span class="jd-spec-v text-base">Indonesia <small>&middot; WIT</small></span>
                    </div>
                </div>

                <p class="mt-5 font-mono text-[10px] uppercase leading-relaxed tracking-[0.16em] text-[#445876]">
                    &gt; respon rata-rata &lt; 2 jam pada hari kerja
                </p>

                @if ($waHero !== '')
                    <a href="https://wa.me/{{ $waHero }}?text={{ rawurlencode((string) config('contact.whatsapp_message')) }}"
                       target="_blank" rel="noopener"
                       class="group mt-6 inline-flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.16em] text-[#7d90b0] transition-colors hover:text-tinta focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen-terang">
                        Konsultasi via WhatsApp
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:translate-x-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- ================= 4. LAYANAN ================= --}}
    <section id="layanan" class="border-t border-garis px-4 py-24">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-col gap-5 border-b border-garis pb-7 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between sm:gap-5">
                <div>
                    <p class="jd-eyebrow">Layanan</p>
                    <h2 class="jd-title mt-4 text-[clamp(2rem,4.5vw,3.25rem)]">Yang bisa saya kerjakan</h2>
                </div>
                <p class="max-w-xs text-[13px] leading-relaxed text-[#7e8ea9]">
                    Harga dasar transparan. Estimasi final menyesuaikan skala &amp; kompleksitas project.
                </p>
            </div>

            <div class="mt-2">
                @forelse ($subjects as $i => $subject)
                    @php $custom = $subject->is_custom || $subject->base_price === null; @endphp
                    <a href="{{ route('order.create', ['subject' => $subject->id]) }}"
                       class="jd-svc jd-reveal"
                       style="--d: {{ 0.03 * $i }}s">
                        <span class="jd-svc-head">
                            <span class="jd-svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="jd-svc-name">{{ $subject->name }}</span>
                            <span class="jd-svc-meta">
                                <span class="jd-svc-price">
                                    @if ($custom)
                                        Konsultasi
                                    @else
                                        Rp{{ number_format($subject->base_price, 0, ',', '.') }}
                                        <span class="text-[#4e6180]">/ {{ $subject->unit ?: 'project' }}</span>
                                    @endif
                                </span>
                                <span class="jd-svc-arrow" aria-hidden="true">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h15m0 0-5.5-5.5M19 12l-5.5 5.5"/>
                                    </svg>
                                </span>
                            </span>
                        </span>
                        @if ($subject->description)
                            <span class="jd-svc-desc">{{ $subject->description }}</span>
                        @endif
                    </a>
                @empty
                    <p class="py-10 text-sm text-[#7e8ea9]">Belum ada layanan aktif.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ================= 5. TECH STACK — keycaps ================= --}}
    <section class="border-y border-garis bg-[#050a13] px-4 py-24">
        <div class="mx-auto grid max-w-6xl items-center gap-14 lg:grid-cols-12">

            <div class="lg:col-span-4">
                <p class="jd-eyebrow">Tech Stack</p>
                <h2 class="jd-title mt-4 text-[clamp(2rem,4.5vw,3.25rem)]">Alat yang saya pakai</h2>
                <p class="mt-5 text-sm leading-relaxed text-[#7e8ea9]">
                    Dari frontend, backend, sampai database dan perangkat. Tekan salah satu tombolnya.
                </p>

                <div class="jd-readout mt-8">
                    <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-[#4f6180]">Teknologi terpilih</p>
                    <p class="mt-2 font-mono text-sm text-[#7fb2ff]" id="jd-readout-name">
                        Belum ada yang dipilih
                    </p>
                </div>
            </div>

            <div class="jd-keycaps-wrap lg:col-span-8">
                <div class="jd-keycaps" id="jd-keycaps">
                    @foreach ($keycaps as [$nama, $glyph, $warna])
                        <button type="button"
                                class="jd-keycap"
                                style="--brand: {{ $warna }}"
                                data-tech="{{ $nama }}"
                                aria-label="{{ $nama }}">
                            <span class="jd-keycap-glyph">{{ $glyph }}</span>
                            <span class="jd-keycap-label">{{ $nama }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ================= 6. CARA PESAN ================= --}}
    <section class="px-4 py-24">
        <div class="mx-auto grid max-w-6xl gap-14 lg:grid-cols-12">

            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-28">
                    <p class="jd-eyebrow">Alur kerja</p>
                    <h2 class="jd-title mt-4 text-[clamp(2rem,4.5vw,3.25rem)]">Cara pesan</h2>
                    <p class="mt-5 max-w-xs text-[13px] leading-relaxed text-[#7e8ea9]">
                        Empat langkah, tanpa tahap yang tidak perlu. Semua transparan sejak awal.
                    </p>

                    <div class="mt-8 inline-flex items-center gap-3 rounded-lg border border-garis bg-[#070c17] px-4 py-3">
                        <span class="font-mono text-[10px] uppercase tracking-[0.18em] text-[#4f6180]">Estimasi mulai</span>
                        <span class="font-display text-lg font-bold text-[#cfe0ff]">Rp50.000</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <ol class="jd-steps">
                    @foreach ($langkah as [$no, $judul, $desc])
                        <li class="jd-step jd-reveal">
                            <span class="jd-step-node" aria-hidden="true"></span>
                            <span class="jd-step-num">{{ $no }}</span>
                            <h3 class="jd-step-title">{{ $judul }}</h3>
                            <p class="jd-step-desc">{{ $desc }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- ================= 7. KATA KLIEN ================= --}}
    @if ($reviews->isNotEmpty())
        <section class="border-y border-garis bg-[#050a13] py-24">
            <div class="mx-auto max-w-6xl px-4">
                <div class="flex flex-col gap-5 border-b border-garis pb-7 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between sm:gap-5">
                    <div>
                        <p class="jd-eyebrow">Ulasan</p>
                        <h2 class="jd-title mt-4 text-[clamp(2rem,4.5vw,3.25rem)]">Kata klien</h2>
                    </div>
                    <p class="max-w-xs text-[13px] leading-relaxed text-[#7e8ea9]">
                        Arahkan kursor ke kartu untuk berhenti dan membacanya.
                    </p>
                </div>
            </div>

            <div class="jd-pita mt-10" style="--jd-gap: 20px; --jd-durasi: 70s; --jd-fade: 8%;">
                <div class="jd-jalur" data-jd-gandakan>
                    @foreach ($reviews as $review)
                        <article class="w-[320px] shrink-0 rounded-2xl border border-garis bg-permukaan p-6 sm:w-[360px]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#132244] font-mono text-xs font-bold text-[#7fb2ff]">
                                        {{ mb_strtoupper(mb_substr($review->displayName(), 0, 1)) }}
                                    </span>
                                    <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-[#5f7396]">
                                        Terverifikasi
                                    </span>
                                </div>
                                <span class="text-[11px] tracking-[0.2em] text-[#f5b301]" aria-label="{{ $review->rating }} dari 5">
                                    {{ str_repeat('★', (int) $review->rating) }}
                                </span>
                            </div>
                            <p class="mt-5 text-sm leading-relaxed text-[#b6c4dc]">
                                {{ $review->comment ?: '(tanpa komentar)' }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ================= 8. CTA ================= --}}
    <section class="px-4 py-24">
        <div class="jd-cta mx-auto max-w-4xl">
            <span class="jd-cta-mark" aria-hidden="true">LET'S BUILD</span>
            <span class="jd-corner jd-corner--tl" aria-hidden="true"></span>
            <span class="jd-corner jd-corner--tr" aria-hidden="true"></span>
            <span class="jd-corner jd-corner--bl" aria-hidden="true"></span>
            <span class="jd-corner jd-corner--br" aria-hidden="true"></span>

            <div class="relative">
                <p class="jd-eyebrow">Mulai sekarang</p>
                <h2 class="jd-title mt-4 text-[clamp(1.9rem,4.5vw,3rem)]">Siap mulai project?</h2>
                <p class="mx-auto mt-5 max-w-lg text-sm leading-relaxed text-redup">
                    Estimasi harga langsung keluar saat Anda mengisi form — mulai dari
                    <span class="font-semibold text-white">Rp50.000</span>, tanpa tanya-tanya dulu.
                </p>
                <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a href="{{ route('order.create') }}"
                       class="inline-flex w-full items-center justify-center rounded-xl bg-aksen-terang px-7 py-3.5 text-sm font-semibold text-kertas transition hover:-translate-y-0.5 hover:bg-[#60a5fa] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen-terang sm:w-auto">
                        Kirim Permintaan
                    </a>
                    <a href="{{ route('track.form') }}"
                       class="inline-flex w-full items-center justify-center rounded-xl border border-[#24314d] px-7 py-3.5 text-sm font-semibold text-[#c8d6ef] transition hover:border-aksen-terang hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen-terang sm:w-auto">
                        Cek Progress
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // --- Keycaps: readout mengikuti tombol yang disentuh ---
        (function () {
            var wrap = document.getElementById('jd-keycaps');
            var out = document.getElementById('jd-readout-name');
            if (!wrap || !out) return;

            var buttons = wrap.querySelectorAll('.jd-keycap');

            function pilih(el) {
                buttons.forEach(function (b) { b.classList.remove('is-active'); });
                el.classList.add('is-active');
                out.textContent = el.getAttribute('data-tech');
            }

            buttons.forEach(function (b) {
                b.addEventListener('mouseenter', function () { pilih(b); });
                b.addEventListener('focus', function () { pilih(b); });
                b.addEventListener('click', function () { pilih(b); });
            });
        })();

        // --- Pita kata kunci ---
        // Desktop: kelompok digandakan sampai lebih lebar dari layar, lalu
        // disalin sekali lagi supaya animasi translateX(-50%) mulus.
        // Mobile: pita statis (lihat media query di app.css), jadi tidak
        // digandakan sama sekali agar semua kata tampil utuh.
        (function () {
            var track = document.getElementById('jd-strip');
            var group = document.getElementById('jd-strip-group');
            if (!track || !group) return;
            if (window.matchMedia('(max-width: 768px)').matches) return;

            var asli = group.innerHTML;
            var lebarTarget = window.innerWidth * 1.15;
            var n = 1;

            var ukur = function () {
                return group.getBoundingClientRect().width;
            };

            while (ukur() < lebarTarget && n < 12) {
                group.insertAdjacentHTML('beforeend', asli);
                n++;
            }

            // Salinan kedua: memastikan -50% kembali ke posisi awal.
            track.appendChild(group.cloneNode(true));
        })();
    </script>
@endpush
