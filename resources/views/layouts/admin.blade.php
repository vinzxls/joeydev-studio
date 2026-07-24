<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — JoeyDev Studio')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f7f8fc] font-sans text-slate-800 antialiased">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 shrink-0 flex-col border-r border-slate-200 bg-white md:flex">
            <div class="flex items-center gap-2.5 px-6 py-6">
                <span class="text-xl font-extrabold tracking-tight text-slate-900">JD<span class="text-rose-500">.</span></span>
                <span class="rounded-full bg-violet-100 px-2.5 py-0.5 text-xs font-bold text-violet-600">Admin</span>
            </div>
            <nav class="flex flex-1 flex-col gap-1.5 px-4 text-sm">
                @php
                    $nav = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'match' => 'admin.dashboard',
                         'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
                        ['route' => 'admin.orders.index', 'label' => 'Order', 'match' => 'admin.orders.*',
                         'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
                        ['route' => 'admin.subjects.index', 'label' => 'Subject & Layanan', 'match' => 'admin.subjects.*',
                         'icon' => 'M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z'],
                        ['route' => 'admin.pricing.index', 'label' => 'Harga & Add-on', 'match' => 'admin.pricing.*',
                         'icon' => 'M9 7.5l3 4.5m0 0l3-4.5M12 12v5.25M15 12H9m6 3H9m12-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'admin.reviews.index', 'label' => 'Review', 'match' => 'admin.reviews.*',
                         'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
                    ];
                @endphp
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 rounded-full px-4 py-2.5 font-bold transition {{ request()->routeIs($item['match']) ? 'bg-violet-600 text-white shadow-md shadow-violet-600/30' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4.5 w-4.5 shrink-0" style="width:18px;height:18px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="border-t border-slate-100 p-4">
                <a href="{{ route('home') }}" target="_blank" class="block rounded-full px-4 py-2 text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">Lihat Website ↗</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="w-full rounded-full px-4 py-2 text-left text-sm font-bold text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">Keluar ({{ auth()->user()?->name }})</button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-40 flex items-center justify-between border-b border-slate-200 bg-white/90 px-4 py-3 backdrop-blur md:hidden">
                <span class="font-extrabold text-slate-900">JD<span class="text-rose-500">.</span> <span class="rounded-full bg-violet-100 px-2 py-0.5 text-[11px] font-bold text-violet-600">Admin</span></span>
                <nav class="flex gap-1 text-xs font-bold">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full px-2.5 py-1.5 {{ request()->routeIs('admin.dashboard') ? 'bg-violet-600 text-white' : 'text-slate-500' }}">Dash</a>
                    <a href="{{ route('admin.orders.index') }}" class="rounded-full px-2.5 py-1.5 {{ request()->routeIs('admin.orders.*') ? 'bg-violet-600 text-white' : 'text-slate-500' }}">Order</a>
                    <a href="{{ route('admin.subjects.index') }}" class="rounded-full px-2.5 py-1.5 {{ request()->routeIs('admin.subjects.*') ? 'bg-violet-600 text-white' : 'text-slate-500' }}">Subject</a>
                    <a href="{{ route('admin.pricing.index') }}" class="rounded-full px-2.5 py-1.5 {{ request()->routeIs('admin.pricing.*') ? 'bg-violet-600 text-white' : 'text-slate-500' }}">Harga</a>
                    <a href="{{ route('admin.reviews.index') }}" class="rounded-full px-2.5 py-1.5 {{ request()->routeIs('admin.reviews.*') ? 'bg-violet-600 text-white' : 'text-slate-500' }}">Review</a>
                </nav>
            </header>

            <main class="flex-1 p-4 md:p-8">
                @if (session('status'))
                    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-medium text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-sm text-rose-800">
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
    </div>
    @stack('scripts')
</body>
</html>
