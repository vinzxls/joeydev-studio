{{--
    Kartu layanan — BLUEPRINT.md §5 (Konten).

    Kartu untuk satu jasa: nama, deskripsi, dan baris harga. Harga memakai
    aksen biru karena ia angka/tautan yang penting — satu-satunya titik aksen
    di dalam kartu, sisanya dijaga netral supaya aksen tidak kehilangan arti
    (§3).

    Props:
      nama      — nama layanan (judul).
      deskripsi — penjelasan singkat.
      harga     — harga dasar (int|null); null berarti custom/konsultasi.
      satuan    — satuan harga, mis. "mulai", "per proyek".
      custom    — true bila harga dikonfirmasi manual setelah konsultasi.
      gelap     — kartu inverted.
--}}
@props([
    'nama' => '',
    'deskripsi' => '',
    'harga' => null,
    'satuan' => '',
    'custom' => false,
    'gelap' => false,
])

<x-jd.kartu :gelap="$gelap" :kurung="$gelap" hover class="flex h-full flex-col p-6">
    <h3 class="font-display text-lg font-bold leading-snug">{{ $nama }}</h3>

    @if ($deskripsi)
        <p class="mt-2 text-sm leading-relaxed {{ $gelap ? 'text-kertas/70' : 'text-redup' }}">
            {{ $deskripsi }}
        </p>
    @endif

    <div class="mt-auto pt-5">
        @if ($custom)
            <span class="font-mono text-[11px] uppercase tracking-[0.18em] {{ $gelap ? 'text-kertas/70' : 'text-redup' }}">
                Konsultasi
            </span>
        @else
            <span class="font-mono text-[11px] uppercase tracking-[0.18em] {{ $gelap ? 'text-kertas/70' : 'text-redup' }}">
                Mulai dari
            </span>
            <p class="mt-1">
                <span class="font-display text-xl font-extrabold text-aksen {{ $gelap ? 'text-aksen-terang' : '' }}">
                    Rp{{ number_format($harga, 0, ',', '.') }}
                </span>
                <span class="ml-1 text-sm {{ $gelap ? 'text-kertas/70' : 'text-redup' }}">{{ $satuan }}</span>
            </p>
        @endif
    </div>
</x-jd.kartu>
