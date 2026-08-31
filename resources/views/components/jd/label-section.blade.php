{{--
    Label section — BLUEPRINT.md §5 (Fondasi) & §4.2 (aturan bahasa).

    Baris metadata kecil di atas judul section. Inilah satu-satunya tempat
    bahasa Inggris diizinkan: path teknis seperti /layanan atau /proses.
    Teks labelnya sendiri tetap bahasa Indonesia.

    Contoh:
      <x-jd.label-section path="/layanan">Yang bisa saya kerjakan</x-jd.label-section>
--}}
@props(['path' => null])

<p {{ $attributes->class('flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.18em] text-redup') }}>
    @if ($path)
        <span class="text-aksen">{{ $path }}</span>
        <span aria-hidden="true" class="h-px w-4 bg-garis"></span>
    @endif
    {{ $slot }}
</p>
