<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — JoeyDev Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@75..125,400..800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Halaman login memakai kanvas gelap penuh, bukan kanvas terang admin:
     ini satu-satunya layar admin yang tidak menampilkan data. --}}
<body class="relative flex min-h-screen items-center justify-center overflow-hidden bg-kertas px-4 font-sans antialiased">
    <div class="jd-grid pointer-events-none absolute inset-0 opacity-70" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(720px_circle_at_50%_18%,rgba(29,78,216,0.28),transparent_66%)]" aria-hidden="true"></div>

    <div class="relative w-full max-w-[380px]">

        <div class="mb-7 text-center">
            <p class="ad-display text-2xl leading-none text-tinta">JD<span class="text-aksen-terang">.</span></p>
            <h1 class="ad-display mt-3 text-[1.75rem] leading-none text-tinta">Login Admin<span class="text-aksen-terang">.</span></h1>
            <p class="ad-mono mt-3 text-[10px] uppercase tracking-[0.2em] text-[#4f6180]">JoeyDev Studio</p>
        </div>

        <form method="POST" action="{{ route('admin.login.attempt') }}"
              class="rounded-lg border border-garis bg-permukaan p-6 shadow-2xl shadow-black/50">

            @csrf

            @error('email')
                <div class="mb-5 rounded border border-rose-500/30 bg-rose-500/10 px-3.5 py-2.5 text-[12.5px] font-medium text-rose-300">
                    {{ $message }}
                </div>
            @enderror

            <div>
                <label for="email" class="ad-mono mb-2 block text-[9.5px] uppercase tracking-[0.16em] text-[#5c6f8f]">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       autocomplete="username"
                       class="w-full rounded border border-garis bg-[#070c17] px-3.5 py-2.5 text-[13px] text-tinta placeholder:text-[#3f5170] transition focus:border-aksen-terang focus:outline-none focus:ring-2 focus:ring-aksen-terang/25">
            </div>

            <div class="mt-4">
                <label for="password" class="ad-mono mb-2 block text-[9.5px] uppercase tracking-[0.16em] text-[#5c6f8f]">Password</label>
                <input id="password" type="password" name="password" required
                       autocomplete="current-password"
                       class="w-full rounded border border-garis bg-[#070c17] px-3.5 py-2.5 text-[13px] text-tinta placeholder:text-[#3f5170] transition focus:border-aksen-terang focus:outline-none focus:ring-2 focus:ring-aksen-terang/25">
            </div>

            <label class="mt-4 flex items-center gap-2.5 text-[12.5px] font-medium text-redup">
                <input type="checkbox" name="remember" class="h-3.5 w-3.5 rounded-sm accent-[#3b82f6]">
                Ingat saya
            </label>

            <button type="submit"
                    class="mt-6 w-full rounded bg-aksen py-3 text-[13px] font-bold text-white transition hover:bg-[#1e40af] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen-terang focus-visible:ring-offset-2 focus-visible:ring-offset-permukaan">
                Masuk
            </button>

            <div class="mt-5 flex items-center gap-2 border-t border-garis pt-4">
                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400"></span>
                <p class="ad-mono text-[9px] uppercase leading-relaxed tracking-[0.14em] text-[#465975]">
                    Area terbatas &middot; akses tercatat
                </p>
            </div>
        </form>

        <p class="mt-6 text-center">
            <a href="{{ route('home') }}" class="ad-mono text-[10px] uppercase tracking-[0.14em] text-[#4f6180] transition-colors hover:text-redup">
                &larr; Kembali ke website
            </a>
        </p>
    </div>
</body>
</html>
