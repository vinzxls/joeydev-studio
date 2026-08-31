{{--
    Kurung sudut / crop mark — BLUEPRINT.md §5 (Fondasi).

    Tanda siku di empat pojok yang membuat sebuah blok terbaca sebagai objek
    modular yang sengaja dirancang, bukan kotak biasa. Ini detail kecil yang
    paling menentukan karakter "technical system" pada referensi.

    Dibuat sebagai span absolut di dalam wadah ber-`relative`, bukan sebagai
    border penuh, supaya tidak menambah ketebalan garis kartu.

    Props:
      terang — pakai true di atas latar gelap (kartu inverted).
--}}
@props(['terang' => false])

@php
    $warna = $terang ? 'border-kertas/55' : 'border-tinta/40';
@endphp

<span aria-hidden="true" class="pointer-events-none absolute inset-0">
    <span class="absolute -left-px -top-px h-3 w-3 border-l border-t {{ $warna }}"></span>
    <span class="absolute -right-px -top-px h-3 w-3 border-r border-t {{ $warna }}"></span>
    <span class="absolute -bottom-px -left-px h-3 w-3 border-b border-l {{ $warna }}"></span>
    <span class="absolute -bottom-px -right-px h-3 w-3 border-b border-r {{ $warna }}"></span>
</span>
