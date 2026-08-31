{{--
    Chip teknologi — BLUEPRINT.md §5 (Fondasi) & pita marquee.

    Titik berwarna diambil lewat style inline (warna datang dari data, bukan
    token), lalu nama stack memakai font-sans kecil tebal. Pill bulat penuh.

    Props:
      nama  — nama teknologi.
      warna — string CSS untuk titik warna.
--}}
@props([
    'nama' => '',
    'warna' => '#1d4ed8',
])

<span {{ $attributes->class('inline-flex items-center gap-2 rounded-full border border-garis bg-permukaan px-4 py-1.5 font-sans text-xs font-bold text-tinta') }}>
    <span aria-hidden="true" class="h-2 w-2 rounded-full" style="background: {{ $warna }}"></span>
    {{ $nama }}
</span>
