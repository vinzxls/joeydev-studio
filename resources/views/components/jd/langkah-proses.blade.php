{{--
    Langkah proses — BLUEPRINT.md §5 (Konten) & §6.3 (Proses).

    Satu langkah dari pipeline 01→04. Nomor memakai font-mono dengan nol
    di depan, aksen biru hanya di nomornya — judul dan deskripsi tetap netral.

    Props:
      nomor     — urutan langkah (int), dirender dua digit.
      judul     — nama tahap.
      deskripsi — penjelasan singkat.
      gelap     — kartu inverted.
--}}
@props([
    'nomor' => 1,
    'judul' => '',
    'deskripsi' => '',
    'gelap' => false,
    'kertas' => false,
])

<x-jd.kartu :gelap="$gelap" :kertas="$kertas" class="flex h-full flex-col p-6">
    <span class="font-mono text-3xl font-bold {{ $gelap ? 'text-aksen-terang' : 'text-aksen' }}">
        {{ str_pad((string) $nomor, 2, '0', STR_PAD_LEFT) }}
    </span>

    <h3 class="mt-4 font-display text-base font-bold leading-snug">{{ $judul }}</h3>

    @if ($deskripsi)
        <p class="mt-2 text-sm leading-relaxed {{ $gelap ? 'text-kertas/70' : 'text-redup' }}">
            {{ $deskripsi }}
        </p>
    @endif
</x-jd.kartu>
