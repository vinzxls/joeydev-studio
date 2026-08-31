{{--
    Tombol sekunder — BLUEPRINT.md §5 (Aksi).

    Aksi pendamping: bobotnya harus jelas di bawah tombol utama, tapi tetap
    terbaca sebagai tombol. Hanya border, tanpa isian.

    Props:
      href — kalau diisi dirender sebagai <a>, kalau tidak sebagai <button>.
      ikon — set false untuk menyembunyikan slot ikon.
--}}
@props([
    'href' => null,
])

@php
    $tag = $href ? 'a' : 'button';
    $kelas = 'group inline-flex items-center justify-center gap-2.5 rounded-kartu border border-garis '
        .'bg-transparent px-6 py-3.5 font-sans text-sm font-bold text-tinta transition duration-200 '
        .'hover:border-tinta '
        .'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen focus-visible:ring-offset-2 '
        .'focus-visible:ring-offset-kertas';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $attributes->get('type', 'button') }}" @endif
    {{ $attributes->except('type')->class($kelas) }}
>
    {{ $slot }}
</{{ $tag }}>
