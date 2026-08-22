@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden">
        {{-- Dekorasi konstelasi ala referensi --}}
        <svg class="pointer-events-none absolute inset-0 h-full w-full opacity-60" xmlns="http://www.w3.org/2000/svg">
            <g stroke="#c7d2fe" stroke-width="1">
                <line x1="8%" y1="20%" x2="20%" y2="35%"/><line x1="20%" y1="35%" x2="14%" y2="60%"/>
                <line x1="78%" y1="18%" x2="90%" y2="30%"/><line x1="90%" y1="30%" x2="82%" y2="52%"/>
                <line x1="82%" y1="52%" x2="93%" y2="66%"/><line x1="30%" y1="80%" x2="45%" y2="88%"/>
            </g>
            <g>
                <circle cx="8%" cy="20%" r="4" fill="#a5b4fc"/><circle cx="20%" cy="35%" r="3" fill="#f9a8d4"/>
                <circle cx="14%" cy="60%" r="5" fill="#ddd6fe"/><circle cx="78%" cy="18%" r="4" fill="#f9a8d4"/>
                <circle cx="90%" cy="30%" r="3" fill="#a5b4fc"/><circle cx="82%" cy="52%" r="5" fill="#ddd6fe"/>
                <circle cx="93%" cy="66%" r="3" fill="#6ee7b7"/><circle cx="30%" cy="80%" r="3" fill="#a5b4fc"/>
                <circle cx="45%" cy="88%" r="4" fill="#fcd34d"/><circle cx="60%" cy="10%" r="3" fill="#ddd6fe"/>
            </g>
        </svg>

        <div class="relative mx-auto max-w-6xl px-4 pb-16 pt-24 text-center md:pt-36 md:pb-24">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Halo, selamat datang di</p>
            <h1 class="mx-auto mt-4 max-w-4xl text-4xl font-extrabold leading-[1.1] tracking-tight text-slate-900 sm:text-6xl">
                Joey<span class="bg-gradient-to-r from-violet-600 via-indigo-500 to-sky-500 bg-clip-text text-transparent">Dev</span> Studio<span class="text-violet-600">.</span>
            </h1>
            <p class="mx-auto mt-5 max-w-2xl text-base text-slate-500 sm:text-lg">
                Jasa pembuatan <span class="rounded-lg bg-sky-100 px-2 py-0.5 font-bold text-sky-700">website</span>,
                <span class="rounded-lg bg-emerald-100 px-2 py-0.5 font-bold text-emerald-700">aplikasi</span> &amp;
                <span class="rounded-lg bg-violet-100 px-2 py-0.5 font-bold text-violet-700">project IT</span> —
                estimasi harga transparan langsung keluar, progress bisa dipantau kapan saja.
            </p>

            <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ route('order.create') }}"
                   class="flex w-full items-center justify-center gap-2 rounded-full bg-violet-600 px-8 py-3.5 font-bold text-white shadow-lg shadow-violet-600/30 transition hover:-translate-y-0.5 hover:bg-violet-700 sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>
                    Pesan Sekarang
                </a>
                <a href="{{ route('track.form') }}"
                   class="flex w-full items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-8 py-3.5 font-bold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-400 sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    Cek Progress
                </a>
            </div>

            <div class="mx-auto mt-14 grid max-w-2xl grid-cols-3 gap-4">
                @foreach ([
                    [$stats['completed'], 'Project Selesai', 'text-violet-600'],
                    [$stats['avg_rating'] > 0 ? number_format($stats['avg_rating'], 1).' ★' : '— ★', 'Rata-rata Rating', 'text-amber-500'],
                    [$stats['review_count'], 'Ulasan Klien', 'text-emerald-600'],
                ] as [$value, $label, $color])
                    <div class="rounded-3xl border border-slate-200/80 bg-white/80 px-3 py-5 shadow-sm backdrop-blur">
                        <p class="text-3xl font-extrabold {{ $color }}">{{ $value }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tentang Saya --}}
    <section class="bg-white py-16" id="tentang">
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
