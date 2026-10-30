<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — JoeyDev Studio')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@75..125,400..800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Chrome gelap, kanvas terang. Token & kelas .ad ada di app.css. --}}
<body class="ad">

@php
    // Kelompok menu sidebar. Dipisah supaya "Ringkasan" tidak bercampur
    // dengan menu operasional harian.
    $menuRingkasan = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'match' => 'admin.dashboard',
         'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
    ];

    $menuOperasional = [
        ['route' => 'admin.orders.index', 'label' => 'Order', 'match' => 'admin.orders.*',
         'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
        ['route' => 'admin.subjects.index', 'label' => 'Subject & Layanan', 'match' => 'admin.subjects.*',
         'icon' => 'M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z'],
        ['route' => 'admin.pricing.index', 'label' => 'Harga & Add-on', 'match' => 'admin.pricing.*',
         'icon' => 'M9 7.5l3 4.5m0 0l3-4.5M12 12v5.25M15 12H9m6 3H9m12-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['route' => 'admin.reviews.index', 'label' => 'Review', 'match' => 'admin.reviews.*',
         'icon' => 'M11.48 3.5a.562.562 0 011.04 0l2.125 5.11a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.98 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557L3.04 10.385a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
    ];

    $inisial = mb_strtoupper(mb_substr(auth()->user()?->name ?? 'AD', 0, 2));
@endphp

{{-- ================= SIDEBAR ================= --}}
<aside class="ad-side">
    <div class="ad-side-brand">
        <a href="{{ route('admin.dashboard') }}" class="ad-side-logo">JD<span>.</span></a>
        <span class="ad-side-tag">Admin</span>
    </div>

    <p class="ad-side-sec">Ringkasan</p>
    <nav class="ad-side-nav" aria-label="Ringkasan">
        @foreach ($menuRingkasan as $item)
            <a href="{{ route($item['route']) }}"
               @class(['ad-side-item', 'is-on' => request()->routeIs($item['match'])])
               @if (request()->routeIs($item['match'])) aria-current="page" @endif>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <p class="ad-side-sec">Operasional</p>
    <nav class="ad-side-nav" aria-label="Operasional">
        @foreach ($menuOperasional as $item)
            <a href="{{ route($item['route']) }}"
               @class(['ad-side-item', 'is-on' => request()->routeIs($item['match'])])
               @if (request()->routeIs($item['match'])) aria-current="page" @endif>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                </svg>
                {{ $item['label'] }}
                @hasSection('nav-badge-' . $item['route'])
                    <span class="ad-side-badge">@yield('nav-badge-' . $item['route'])</span>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="ad-side-foot">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="ad-side-link">
            Lihat website
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
            </svg>
        </a>

        <div class="ad-side-user">
            <span class="ad-side-avatar">{{ $inisial }}</span>
            <div class="min-w-0">
                <p class="ad-side-name truncate">{{ auth()->user()?->name ?? 'Administrator' }}</p>
                <p class="ad-side-role">Administrator</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="ad-side-link text-left">Keluar</button>
        </form>
    </div>
</aside>

{{-- ================= MAIN ================= --}}
<div class="ad-main">

    <header class="ad-top">
        <div class="ad-crumb">
            <span>Admin</span>
            <i aria-hidden="true">/</i>
            <b>@yield('crumb', 'Dashboard')</b>
        </div>
        <div class="ad-top-actions">
            @yield('top-actions')
        </div>
    </header>

    {{-- Navigasi mobile: sidebar disembunyikan di bawah 860px --}}
    <nav class="flex gap-1 overflow-x-auto border-b border-[#e4e7ec] bg-white px-3 py-2 md:hidden" aria-label="Navigasi admin">
        @foreach (array_merge($menuRingkasan, $menuOperasional) as $item)
            <a href="{{ route($item['route']) }}"
               @class([
                   'shrink-0 rounded px-2.5 py-1.5 font-mono text-[10px] uppercase tracking-[0.1em] transition',
                   'bg-[#1d4ed8] text-white' => request()->routeIs($item['match']),
                   'text-[#667085] hover:bg-[#f4f6fb]' => ! request()->routeIs($item['match']),
               ])>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <main class="ad-page flex-1">
        @if (session('status'))
            <div class="ad-alert ad-alert-ok">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="ad-alert ad-alert-err">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
