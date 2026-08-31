{{--
    Tombol utama — BLUEPRINT.md §5 (Aksi) & §8 (Motion).

    Aksi paling penting di sebuah section. Sengaja memakai blok gelap, BUKAN
    biru: aksen biru dijatah untuk satu frasa headline, tautan, menu aktif, dan
    status (§3). Kalau semua tombol biru, aksennya kehilangan arti.

    Panah bergeser 4px saat disentuh kursor — satu-satunya gerakan yang perlu.

    Props:
      href  — kalau diisi dirender sebagai <a>, kalau tidak sebagai <button>.
      panah — set false untuk menyembunyikan panah.
--}}
@props([
    'href' => null,
    'panah' => true,
])

@php
    $tag = $href ? 'a' : 'button';
    $kelas = 'group inline-flex items-center justify-center gap-2.5 rounded-kartu border border-tinta '
        .'bg-tinta px-6 py-3.5 font-sans text-sm font-bold text-kertas transition duration-200 '
        .'hover:bg-transparent hover:text-tinta '
        .'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen focus-visible:ring-offset-2 '
        .'focus-visible:ring-offset-kertas';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $attributes->get('type', 'button') }}" @endif
    {{ $attributes->except('type')->class($kelas) }}
>
    {{ $slot }}

    @if ($panah)
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
             class="h-4 w-4 shrink-0 transition-transform duration-200 group-hover:translate-x-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h15m0 0-5.5-5.5M19 12l-5.5 5.5" />
        </svg>
    @endif
</{{ $tag }}>
