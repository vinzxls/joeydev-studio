<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JoeyDev Studio — Jasa Pembuatan Software & Project IT')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Archivo = display (punya sumbu lebar wdth untuk headline panjang),
         Plus Jakarta Sans = teks UI, JetBrains Mono = label metadata teknis.
         Berat Plus Jakarta masih lengkap karena markup lama memakai 500 & 800;
         dipangkas ke 3 berat pada fase poles. Lihat BLUEPRINT.md §4.1. --}}
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@75..125,400..800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-kertas font-sans text-slate-800 antialiased">

    {{-- Splash pembuka: sapaan 10 bahasa, keluar dengan efek tirai terangkat --}}
    @if (request()->routeIs('home'))
        <div id="splash" class="jd-grid fixed inset-0 z-[100] flex items-center justify-center bg-kertas">
            <p id="splash-word" class="jd-judul text-4xl text-tinta sm:text-6xl"><span id="splash-text" style="display:inline-block">Halo</span></p>
        </div>
        <style>
            #splash {
                transition: transform .7s cubic-bezier(.76, 0, .24, 1), border-radius .7s cubic-bezier(.76, 0, .24, 1);
                will-change: transform;
            }
            #splash.splash-exit {
                transform: translateY(-100%);
                border-bottom-left-radius: 50% 10rem;
                border-bottom-right-radius: 50% 10rem;
            }
            /* Konten tampil secara default; splash hanya menyembunyikannya sementara.
               Jika transisi gagal karena alasan apa pun, konten tetap terlihat. */
            main, footer {
                transition: opacity .6s ease .15s, transform .7s cubic-bezier(.22, 1, .36, 1) .15s;
            }
            body.splash-active main, body.splash-active footer {
                opacity: 0;
                transform: translateY(24px);
            }
        </style>
    @endif

    <x-jd.navbar />

    @if (session('status'))
        <div class="mx-auto max-w-[1280px] px-4 pt-8 md:px-8">
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @php $waNumber = preg_replace('/\D/', '', (string) config('contact.whatsapp')); @endphp
    @if ($waNumber !== '')
        {{-- Tombol WhatsApp mengambang di pojok kanan bawah. Sejak navbar pindah
             ke atas (Fase 2), tidak perlu lagi diangkat untuk menghindarinya. --}}
        <a href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode(config('contact.whatsapp_message')) }}"
           target="_blank" rel="noopener"
           class="group fixed bottom-6 right-4 z-40 flex items-center gap-2.5 rounded-full bg-emerald-500 px-4 py-3.5 text-white shadow-lg shadow-emerald-500/40 transition hover:-translate-y-0.5 hover:bg-emerald-600 md:right-6"
           aria-label="Hubungi kami via WhatsApp">
            <svg viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6 shrink-0" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.174.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884a9.82 9.82 0 016.99 2.896 9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
            </svg>
            <span class="max-w-0 overflow-hidden whitespace-nowrap text-sm font-bold transition-all duration-300 group-hover:max-w-40 md:max-w-40">Chat via WhatsApp</span>
        </a>
    @endif

    <x-jd.footer />

    <script>
        (function () {
            const splash = document.getElementById('splash');
            if (!splash) return;
            document.body.classList.add('splash-active');

            const text = document.getElementById('splash-text');
            const greets = ['Halo', 'Hello', 'こんにちは', '안녕하세요', '你好', 'Bonjour', 'Hola', 'Ciao', 'Hallo', 'Olá'];
            let i = 0;

            function next() {
                i++;
                if (i >= greets.length) return finish();
                text.textContent = greets[i];
                // Tiap kata masuk dengan slide-fade singkat agar pergantian terasa halus
                text.animate(
                    [{ opacity: .15, transform: 'translateY(12px)' }, { opacity: 1, transform: 'translateY(0)' }],
                    { duration: 140, easing: 'ease-out' },
                );
                setTimeout(next, i === 1 ? 220 : 150);
            }

            function finish() {
                // Tirai terangkat ke atas, konten beranda naik masuk
                splash.classList.add('splash-exit');
                document.body.classList.remove('splash-active');
                setTimeout(() => splash.remove(), 750);
            }

            setTimeout(next, 420);

            // Failsafe: apa pun yang terjadi, splash tidak boleh menutupi halaman selamanya
            setTimeout(() => {
                if (document.getElementById('splash')) finish();
            }, 5000);
        })();
    </script>
    <script>
        // --- Pita berjalan: gandakan isi sampai cukup panjang untuk loop mulus ---
        (function () {
            var jalur = document.querySelectorAll('[data-jd-gandakan]');
            if (!jalur.length) return;

            function isiUlang(el) {
                if (!el.dataset.jdAsli) el.dataset.jdAsli = el.innerHTML;
                el.innerHTML = el.dataset.jdAsli;

                // Ulangi isi sampai satu set melebihi lebar layar, lalu gandakan
                // set itu — tanpa ini, geseran -50% menyisakan celah kosong.
                var batas = 0;
                while (el.scrollWidth < window.innerWidth && batas < 12) {
                    el.innerHTML += el.dataset.jdAsli;
                    batas++;
                }
                el.innerHTML += el.innerHTML;
            }

            jalur.forEach(isiUlang);

            var tunda;
            window.addEventListener('resize', function () {
                clearTimeout(tunda);
                tunda = setTimeout(function () { jalur.forEach(isiUlang); }, 250);
            });
        })();

        // --- Muncul bertahap saat masuk layar ---
        (function () {
            var blok = document.querySelectorAll('[data-jd-muncul]');
            if (!blok.length) return;

            if (!('IntersectionObserver' in window)) {
                blok.forEach(function (b) { b.classList.add('jd-tampil'); });
                return;
            }

            var pengamat = new IntersectionObserver(function (entri) {
                entri.forEach(function (e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('jd-tampil');
                        pengamat.unobserve(e.target);
                    }
                });
            }, { threshold: 0.25 });

            blok.forEach(function (b) { pengamat.observe(b); });
        })();
    </script>

    @stack('scripts')
</body>
</html>
