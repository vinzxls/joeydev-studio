{{--
    Modul statistik — BLUEPRINT.md §5 (Konten) & Hero.

    Blok angka di hero. Nilai selalu datang dari controller/database lewat
    props — tidak pernah di-hardcode di sini.

    Props:
      nilai — angka utama, mis. "120+" atau "24/7".
      label — keterangan kecil.
--}}
@props([
    'nilai' => '',
    'label' => '',
])

<div {{ $attributes->class('flex flex-col') }}>
    <span class="font-display text-3xl font-extrabold leading-none text-tinta sm:text-4xl">
        {{ $nilai }}
    </span>
    @if ($label)
        <span class="mt-2 font-mono text-[11px] uppercase tracking-[0.18em] text-redup">
            {{ $label }}
        </span>
    @endif
</div>
