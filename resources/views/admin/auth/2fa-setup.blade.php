<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup 2FA — JoeyDev Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#f7f8fc] px-4 py-10 font-sans antialiased">
    <svg class="pointer-events-none absolute inset-0 h-full w-full opacity-60" xmlns="http://www.w3.org/2000/svg">
        <g stroke="#c7d2fe" stroke-width="1">
            <line x1="10%" y1="15%" x2="22%" y2="30%"/><line x1="80%" y1="70%" x2="90%" y2="85%"/>
            <line x1="75%" y1="20%" x2="88%" y2="32%"/>
        </g>
        <g>
            <circle cx="10%" cy="15%" r="4" fill="#a5b4fc"/><circle cx="22%" cy="30%" r="3" fill="#f9a8d4"/>
            <circle cx="80%" cy="70%" r="5" fill="#ddd6fe"/><circle cx="90%" cy="85%" r="3" fill="#6ee7b7"/>
            <circle cx="75%" cy="20%" r="4" fill="#f9a8d4"/><circle cx="88%" cy="32%" r="3" fill="#a5b4fc"/>
            <circle cx="15%" cy="75%" r="4" fill="#fcd34d"/>
        </g>
    </svg>

    <div class="relative w-full max-w-lg">
        <div class="mb-7 text-center">
            <p class="text-3xl font-extrabold tracking-tight text-slate-900">JD<span class="text-rose-500">.</span></p>
            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Aktifkan <span class="bg-gradient-to-r from-violet-600 to-indigo-500 bg-clip-text text-transparent">2FA</span><span class="text-violet-600">.</span></h1>
            <p class="mt-1 text-sm text-slate-500">Lindungi akun admin Anda dengan autentikasi dua faktor.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-900/5">
            <div class="text-center">
                <div class="mx-auto inline-block rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                    <img src="{{ $qrCode }}" alt="QR Code 2FA" class="h-44 w-44">
                </div>
                <p class="mt-3 text-sm text-slate-500">
                    Pindai kode QR ini dengan aplikasi authenticator
                    (Google Authenticator, Authy, dll.).
                </p>
            </div>

            <div class="mt-6">
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Kunci rahasia</label>
                <input type="text" readonly value="{{ $secret }}"
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-center text-sm font-mono tracking-widest text-slate-700">
            </div>

            <div class="mt-6">
                <p class="mb-1.5 text-sm font-bold text-slate-700">Kode pemulihan (simpan dengan aman)</p>
                <p class="mb-2 text-xs text-slate-500">Setiap kode hanya bisa dipakai sekali. Simpan di tempat terpisah dari ponsel Anda.</p>
                <ul class="grid grid-cols-1 gap-2">
                    @foreach ($recoveryCodes as $code)
                        <li class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-center font-mono text-sm font-semibold tracking-widest text-slate-800">{{ $code }}</li>
                    @endforeach
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.2fa.enable') }}" class="mt-6">
                @csrf
                @error('code')
                    <div class="mb-4 rounded-2xl bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700">{{ $message }}</div>
                @enderror
                <label class="mb-1.5 block text-sm font-bold text-slate-700">Ketikan kode 6 digit untuk konfirmasi</label>
                <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code"
                       value="{{ old('code') }}" required autofocus
                       placeholder="000000"
                       class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-center text-xl font-bold tracking-[0.5em] text-slate-900 transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                <button type="submit"
                        class="mt-5 w-full rounded-full bg-violet-600 py-3.5 font-bold text-white shadow-lg shadow-violet-600/30 transition hover:-translate-y-0.5 hover:bg-violet-700">Aktifkan 2FA</button>
            </form>
        </div>

        <p class="mt-5 text-center text-sm"><a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-400 transition hover:text-slate-600">&larr; Kembali ke dashboard</a></p>
    </div>
</body>
</html>
