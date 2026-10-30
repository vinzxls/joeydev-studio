<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JoeyDev Studio — Jasa Pembuatan Software & Project IT')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Archivo = display (punya sumbu lebar wdth untuk headline panjang),
         Plus Jakarta Sans = teks UI, JetBrains Mono = label metadata teknis. --}}
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@75..125,400..800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="jd-kanvas min-h-screen font-sans text-redup antialiased">

    {{-- Splash pembuka: sapaan 10 bahasa, keluar dengan tirai terangkat --}}
    @if (request()->routeIs('home'))
        <div id="jd-splash" class="jd-grid fixed inset-0 z-[100] flex flex-col items-center justify-center overflow-hidden bg-kertas select-none">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(760px_circle_at_50%_50%,rgba(29,78,216,0.3),transparent_66%)]" aria-hidden="true"></div>

            <p class="absolute top-7 left-1/2 -translate-x-1/2 font-display text-[15px] font-extrabold tracking-tight text-[#37455f]">
                JD<span class="text-aksen-terang">.</span>
            </p>

            <div class="relative z-10 flex flex-col items-center">
                <p class="jd-judul text-center text-[clamp(2.75rem,9vw,6.5rem)] leading-none text-white">
                    <span id="jd-splash-text" class="jd-splash-word">Halo</span>
                </p>

                <div id="jd-splash-codes" class="mt-8 flex items-center justify-center gap-1 font-mono text-[10px] tracking-[0.2em]">
                    @foreach (['ID', 'EN', 'JA', 'KO', 'ZH', 'FR', 'ES', 'IT', 'DE', 'PT'] as $i => $kode)
                        <span data-idx="{{ $i }}" class="jd-code {{ $i === 0 ? 'is-on' : '' }}">{{ $kode }}</span>
                    @endforeach
                </div>
            </div>

            <div class="absolute bottom-0 left-1/2 w-[min(320px,60vw)] -translate-x-1/2" aria-hidden="true">
                <div class="h-0.5 overflow-hidden rounded-full bg-garis">
                    <i id="jd-splash-bar" class="block h-full w-0 bg-gradient-to-r from-aksen to-aksen-terang shadow-[0_0_10px_rgba(59,130,246,0.9)] transition-[width] duration-[400ms] ease-out"></i>
                </div>
            </div>

            <p class="absolute bottom-[34px] left-1/2 -translate-x-1/2 whitespace-nowrap font-mono text-[9px] uppercase tracking-[0.26em] text-[#2f3d55]" aria-hidden="true">
                Menyiapkan halaman
            </p>

            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-[#2a4a86] to-transparent" aria-hidden="true"></div>
        </div>
    @endif

    <x-jd.navbar />

    @if (session('status'))
        <div class="mx-auto max-w-[1280px] px-4 pt-8 md:px-8">
            <div class="rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-5 py-3.5 text-sm font-medium text-emerald-300">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @php $waNumber = preg_replace('/\D/', '', (string) config('contact.whatsapp')); @endphp
    @if ($waNumber !== '')
        <a href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode(config('contact.whatsapp_message')) }}"
           target="_blank" rel="noopener"
           class="group fixed bottom-6 right-4 z-40 flex items-center gap-2.5 rounded-full bg-emerald-500 px-4 py-3.5 text-white shadow-lg shadow-emerald-500/25 transition hover:-translate-y-0.5 hover:bg-emerald-600 md:right-6"
           aria-label="Hubungi kami via WhatsApp">
            <svg viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6 shrink-0" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.174.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884a9.82 9.82 0 016.99 2.896 9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
            </svg>
            <span class="max-w-0 overflow-hidden whitespace-nowrap text-sm font-bold transition-all duration-300 group-hover:max-w-40 md:max-w-40">Chat via WhatsApp</span>
        </a>
    @endif

    <x-jd.footer />

    <script>
        // --- Splash: roda sapaan yang melambat lalu berhenti di "Halo" ---
        (function () {
            var splash = document.getElementById('jd-splash');
            if (!splash) return;

            // Sekali per sesi: pengunjung yang kembali tidak diblokir lagi.
            if (sessionStorage.getItem('jd-splash')) {
                splash.remove();
                return;
            }

            var text = document.getElementById('jd-splash-text');
            var bar = document.getElementById('jd-splash-bar');
            var kode = splash.querySelectorAll('#jd-splash-codes [data-idx]');

            // Sapaan asing dulu, "Halo" ditahan paling akhir.
            var urutan = [
                { kata: 'Hello', idx: 1 },
                { kata: 'こんにちは', idx: 2 },
                { kata: '안녕하세요', idx: 3 },
                { kata: '你好', idx: 4 },
                { kata: 'Bonjour', idx: 5 },
                { kata: 'Hola', idx: 6 },
                { kata: 'Ciao', idx: 7 },
                { kata: 'Hallo', idx: 8 },
                { kata: 'Olá', idx: 9 },
                { kata: 'Halo', idx: 0 },
            ];

            // Jeda memanjang menjelang akhir: roda melambat, bukan flicker.
            // Nilai terakhir = berapa lama "Halo" ditahan sebelum tirai naik.
            var jeda = [180, 190, 200, 215, 235, 260, 290, 330, 380, 480];

            var kurangGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var timer = null;

            function tandai(idx) {
                kode.forEach(function (el, n) { el.classList.toggle('is-on', n === idx); });
            }

            function angkat() {
                splash.classList.add('jd-splash-exit');
                sessionStorage.setItem('jd-splash', '1');
                // Harus lebih lama dari durasi transisi transform (0.95s)
                timer = setTimeout(function () { splash.remove(); }, 1000);
            }

            if (kurangGerak) {
                bar.style.width = '100%';
                timer = setTimeout(angkat, 900);
                return;
            }

            var i = 0;
            function langkah() {
                if (i >= urutan.length) return angkat();

                text.textContent = urutan[i].kata;
                tandai(urutan[i].idx);
                bar.style.width = Math.round(((i + 1) / urutan.length) * 100) + '%';

                // Masuk lebih pelan: kabur sedikit lalu mempertajam.
                text.animate(
                    [
                        { opacity: 0.15, transform: 'translateY(14px)', filter: 'blur(6px)' },
                        { opacity: 1, transform: 'translateY(0)', filter: 'blur(0px)' },
                    ],
                    { duration: 170, easing: 'cubic-bezier(0.22, 1, 0.36, 1)' },
                );

                timer = setTimeout(langkah, jeda[i]);
                i++;
            }

            timer = setTimeout(langkah, 240);

            // Failsafe: apa pun yang terjadi, splash tidak boleh menutupi selamanya.
            setTimeout(function () {
                if (document.getElementById('jd-splash')) angkat();
            }, 8000);
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

        // --- Reveal per-elemen (.jd-reveal) + node timeline ---
        (function () {
            var item = document.querySelectorAll('.jd-reveal, .jd-step');
            if (!item.length) return;

            if (!('IntersectionObserver' in window)) {
                item.forEach(function (el) { el.classList.add('is-in'); });
                return;
            }

            var pengamat = new IntersectionObserver(function (entri) {
                entri.forEach(function (e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('is-in');
                        pengamat.unobserve(e.target);
                    }
                });
            }, { threshold: 0.05, rootMargin: '200px 0px 200px 0px' });

            item.forEach(function (el) { pengamat.observe(el); });
        })();
    </script>

    @stack('scripts')
</body>
</html>
