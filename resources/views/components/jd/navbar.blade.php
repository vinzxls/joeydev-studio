{{--
    Navbar — BLUEPRINT.md §6 (01).

    Control bar sticky: tinggi 64px mobile / 72px desktop, transparan di puncak
    halaman lalu memadat saat digulir, border bawah 1px, indikator menu aktif
    berupa kotak kecil warna aksen.

    CATATAN mobile — sengaja TIDAK memakai hamburger (v1.0 sempat menyebutnya).
    Menu situs ini hanya dua tautan plus satu CTA; menyembunyikan "Cek Progress"
    di balik hamburger justru mengubur jalur yang paling sering dipakai klien
    lama. Jadi di mobile semuanya tetap tampil, hanya "Beranda" yang
    disembunyikan karena logo sudah menuju ke sana.
--}}
@php
    $menu = [
        ['route' => 'home', 'label' => 'Beranda', 'aktif' => request()->routeIs('home'), 'mobile' => false],
        ['route' => 'track.form', 'label' => 'Cek Progress', 'aktif' => request()->routeIs('track.*'), 'mobile' => true],
    ];
@endphp

<header id="jd-navbar"
        class="jd-navbar sticky top-0 z-50 border-b border-transparent transition-[background-color,border-color] duration-200">
    <nav aria-label="Navigasi utama"
         class="mx-auto flex h-16 max-w-[1280px] items-center justify-between gap-4 px-4 md:h-[72px] md:px-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
           class="jd-judul shrink-0 text-xl leading-none tracking-tight text-tinta transition-opacity hover:opacity-70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen focus-visible:ring-offset-4 focus-visible:ring-offset-kertas md:text-2xl">
            JD<span class="text-aksen">.</span>
            <span class="sr-only">JoeyDev Studio — kembali ke beranda</span>
        </a>

        {{-- Menu --}}
        <ul class="flex items-center gap-1 md:gap-2">
            @foreach ($menu as $item)
                <li @class(['hidden md:block' => ! $item['mobile']])>
                    <a href="{{ route($item['route']) }}"
                       @if ($item['aktif']) aria-current="page" @endif
                       class="flex items-center gap-2 px-2.5 py-2 font-mono text-[11px] uppercase tracking-[0.14em] transition-colors md:px-3 {{ $item['aktif'] ? 'text-tinta' : 'text-redup hover:text-tinta' }} focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen">
                        <span aria-hidden="true"
                              class="h-1.5 w-1.5 shrink-0 transition-colors {{ $item['aktif'] ? 'bg-aksen' : 'bg-transparent' }}"></span>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach

            {{-- CTA --}}
            <li class="jd-cta-nav ml-1 md:ml-3">
                <a href="{{ route('order.create') }}"
                   class="group inline-flex items-center gap-2 rounded-kartu border border-tinta bg-tinta px-4 py-2.5 font-mono text-[11px] uppercase tracking-[0.14em] text-kertas transition duration-200 hover:bg-transparent hover:text-tinta focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen focus-visible:ring-offset-2 focus-visible:ring-offset-kertas md:px-5">
                    Pesan
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                         class="h-3.5 w-3.5 shrink-0 transition-transform duration-200 group-hover:translate-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h15m0 0-5.5-5.5M19 12l-5.5 5.5" />
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</header>

<script>
    // Transparan di puncak halaman, memadat begitu digulir. Kelasnya diberikan
    // lewat JS karena CSS belum punya cara mendeteksi posisi scroll.
    (function () {
        var bar = document.getElementById('jd-navbar');
        if (!bar) return;
        var tandai = function () {
            bar.classList.toggle('jd-navbar-padat', window.scrollY > 8);
        };
        tandai();
        window.addEventListener('scroll', tandai, { passive: true });

        // CTA navbar bergantian dengan CTA hero: selama hero masih terlihat,
        // tombol di atas disembunyikan supaya ajakan "Pesan" tidak muncul dua
        // kali dalam satu layar. Begitu hero lewat, tombolnya menempel di atas.
        //
        // Default-nya TAMPIL: kalau JS gagal atau halaman tidak punya hero
        // (mis. /pesan, /cek-progress), tombolnya tetap ada.
        //
        // Ditunda sampai DOM siap: komponen navbar dirender SEBELUM <main>,
        // jadi pada saat skrip ini jalan elemen hero belum ada.
        var pasangHero = function () {
            var hero = document.querySelector('[data-jd-hero]');
            if (!hero || !('IntersectionObserver' in window)) return;
            bar.classList.add('jd-navbar-cta-sembunyi');
            new IntersectionObserver(function (entri) {
                bar.classList.toggle('jd-navbar-cta-sembunyi', entri[0].isIntersecting);
            }, { rootMargin: '-72px 0px 0px 0px' }).observe(hero);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', pasangHero);
        } else {
            pasangHero();
        }
    })();
</script>
