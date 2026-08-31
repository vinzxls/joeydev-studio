{{--
    Kartu dasar — BLUEPRINT.md §5 (Konten).

    SEMUA kartu di situs ini mewarisi komponen ini, supaya border, radius, dan
    hover-nya tidak pernah melenceng antar section. Kedalaman datang dari
    border 1px dan kontras blok, bukan dari bayangan (§3).

    Props:
      gelap  — kartu inverted, dipakai selang-seling untuk irama bento.
      kurung — tampilkan kurung sudut.
      hover  — aktifkan angkat + border aksen saat disentuh kursor.
--}}
@props([
    'gelap' => false,
    'kurung' => false,
    'hover' => false,
])

<div
    {{ $attributes->class([
        'relative rounded-kartu border',
        'border-garis bg-permukaan text-tinta' => ! $gelap,
        'border-tinta bg-tinta text-kertas' => $gelap,
        'transition duration-200 hover:-translate-y-[3px]' => $hover,
        'hover:border-aksen' => $hover && ! $gelap,
        'hover:border-aksen-terang' => $hover && $gelap,
    ]) }}
>
    @if ($kurung)
        <x-jd.kurung-sudut :terang="$gelap" />
    @endif

    {{ $slot }}
</div>
