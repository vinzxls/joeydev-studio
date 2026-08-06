<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JoeyDev Studio — Jasa Pembuatan Software & Project IT')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f7f8fc] font-sans text-slate-800 antialiased">

    {{-- Splash pembuka: sapaan 10 bahasa, keluar dengan efek tirai terangkat --}}
    @if (request()->routeIs('home'))
        <div id="splash" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#eceafd]">
            <p id="splash-word" class="text-4xl font-extrabold text-violet-500 sm:text-6xl"><span id="splash-text" style="display:inline-block">Halo</span></p>
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

    {{-- Navbar pill melayang: atas di desktop, bawah di mobile --}}
    <nav class="fixed inset-x-0 bottom-4 z-50 flex justify-center px-4 md:bottom-auto md:top-5">
        <div class="flex items-center gap-1 rounded-full border border-slate-200/70 bg-white/90 py-2 pl-5 pr-2 shadow-lg shadow-slate-900/5 backdrop-blur">
            <a href="{{ route('home') }}" class="mr-2 text-lg font-extrabold tracking-tight text-slate-900">JD<span class="text-rose-500">.</span></a>

            @php
                $navItems = [
                    ['route' => 'home', 'label' => 'Beranda', 'active' => request()->routeIs('home'),
                     'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75'],
                    ['route' => 'track.form', 'label' => 'Cek Progress', 'active' => request()->routeIs('track.*'),
                     'icon' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z'],
                ];
            @endphp

            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-2 rounded-full px-3.5 py-2 text-sm font-semibold transition {{ $item['active'] ? 'bg-violet-600 text-white shadow-md shadow-violet-600/30' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>
                    <span class="hidden sm:inline">{{ $item['label'] }}</span>
                </a>
            @endforeach

            <a href="{{ route('order.create') }}"
               class="ml-1 flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold transition {{ request()->routeIs('order.*') ? 'bg-violet-600 text-white shadow-md shadow-violet-600/30' : 'bg-slate-900 text-white hover:bg-slate-700' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z" />
                </svg>
                <span class="hidden sm:inline">Pesan</span>
            </a>
        </div>
    </nav>

    @if (session('status'))
        <div class="mx-auto max-w-6xl px-4 pt-24">
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main class="pb-24 md:pb-0">
        @yield('content')
    </main>

    @php $waNumber = preg_replace('/\D/', '', (string) config('contact.whatsapp')); @endphp
    @if ($waNumber !== '')
        {{-- Tombol WhatsApp mengambang: di mobile diletakkan di atas navbar bawah --}}
        <a href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode(config('contact.whatsapp_message')) }}"
           target="_blank" rel="noopener"
           class="group fixed bottom-24 right-4 z-40 flex items-center gap-2.5 rounded-full bg-emerald-500 px-4 py-3.5 text-white shadow-lg shadow-emerald-500/40 transition hover:-translate-y-0.5 hover:bg-emerald-600 md:bottom-6 md:right-6"
           aria-label="Hubungi kami via WhatsApp">
            <svg viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6 shrink-0" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.174.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884a9.82 9.82 0 016.99 2.896 9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
            </svg>
            <span class="max-w-0 overflow-hidden whitespace-nowrap text-sm font-bold transition-all duration-300 group-hover:max-w-40 md:max-w-40">Chat via WhatsApp</span>
        </a>
    @endif

    <footer class="mt-20 border-t border-slate-200 bg-white pb-24 md:pb-0">
        <div class="mx-auto flex max-w-6xl flex-col items-center gap-3 px-4 py-8 text-center text-sm text-slate-500 sm:flex-row sm:justify-between sm:text-left">
            <p><span class="font-extrabold text-slate-900">JD<span class="text-rose-500">.</span></span> &copy; {{ date('Y') }} JoeyDev Studio — Jasa pembuatan software, project IT &amp; mentoring pemrograman.</p>
            <span class="flex items-center gap-4">
                @if ($waNumber !== '')
                    <a href="https://wa.me/{{ $waNumber }}?text={{ rawurlencode(config('contact.whatsapp_message')) }}"
                       target="_blank" rel="noopener" class="font-semibold text-emerald-600 transition hover:text-emerald-700">WhatsApp</a>
                @endif
                <a href="{{ route('admin.login') }}" class="text-slate-400 transition hover:text-slate-600">Login Admin</a>
            </span>
        </div>
    </footer>

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
    @stack('scripts')
</body>
</html>
