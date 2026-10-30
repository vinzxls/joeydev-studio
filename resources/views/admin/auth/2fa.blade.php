<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi 2FA — JoeyDev Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@75..125,400..800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative flex min-h-screen items-center justify-center overflow-hidden bg-kertas px-4 font-sans antialiased">
    <div class="jd-grid pointer-events-none absolute inset-0 opacity-70" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(720px_circle_at_50%_18%,rgba(29,78,216,0.28),transparent_66%)]" aria-hidden="true"></div>

    <div class="relative w-full max-w-[380px]">

        <div class="mb-7 text-center">
            <p class="ad-display text-2xl leading-none text-tinta">JD<span class="text-aksen-terang">.</span></p>
            <h1 class="ad-display mt-3 text-[1.75rem] leading-none text-tinta">Verifikasi 2FA<span class="text-aksen-terang">.</span></h1>
            <p class="mx-auto mt-3 max-w-[19rem] text-[12.5px] leading-relaxed text-redup">
                Masukkan kode 6 digit dari aplikasi authenticator Anda, atau gunakan kode pemulihan.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.2fa.verify') }}"
              class="rounded-lg border border-garis bg-permukaan p-6 shadow-2xl shadow-black/50">

            @csrf

            @error('code')
                <div class="mb-5 rounded border border-rose-500/30 bg-rose-500/10 px-3.5 py-2.5 text-[12.5px] font-medium text-rose-300">
                    {{ $message }}
                </div>
            @enderror

            <label for="code" class="ad-mono mb-2 block text-[9.5px] uppercase tracking-[0.16em] text-[#5c6f8f]">Kode 6 digit</label>
            <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code"
                   autofocus required maxlength="10" placeholder="000000"
                   class="ad-mono w-full rounded border border-garis bg-[#070c17] px-3.5 py-3 text-center text-xl font-bold tracking-[0.5em] text-tinta placeholder:text-[#2f3d55] transition focus:border-aksen-terang focus:outline-none focus:ring-2 focus:ring-aksen-terang/25">

            <button type="submit"
                    class="mt-6 w-full rounded bg-aksen py-3 text-[13px] font-bold text-white transition hover:bg-[#1e40af] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen-terang focus-visible:ring-offset-2 focus-visible:ring-offset-permukaan">
                Verifikasi
            </button>
        </form>

        <p class="mt-6 text-center">
            <a href="{{ route('admin.login') }}" class="ad-mono text-[10px] uppercase tracking-[0.14em] text-[#4f6180] transition-colors hover:text-redup">
                &larr; Kembali ke login
            </a>
        </p>
    </div>
</body>
</html>
