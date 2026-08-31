{{--
    Badge status — BLUEPRINT.md §5 (Fondasi).

    Penanda ketersediaan bergaya metadata sistem. Titik aksen memakai animasi
    denyut halus; dimatikan otomatis lewat prefers-reduced-motion di app.css.

    Props:
      terang — pakai true di atas latar gelap.
--}}
@props(['terang' => false])

@php
    $teks = $terang ? 'text-kertas/70' : 'text-redup';
@endphp

<span {{ $attributes->class('inline-flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.14em] '.$teks) }}>
    <span aria-hidden="true" class="relative flex h-1.5 w-1.5">
        <span class="jd-denyut absolute inline-flex h-full w-full bg-aksen opacity-70"></span>
        <span class="relative inline-flex h-1.5 w-1.5 bg-aksen"></span>
    </span>
    {{ $slot }}
</span>
