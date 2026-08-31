{{--
    Kartu testimoni — BLUEPRINT.md §5 (Konten) & §6.5 (Testimoni).

    Kutipan klien dengan tanda kutip, teks, lalu baris footer: rating bintang
    (amber adalah warna semantik rating, dibagi dengan pemakaian amber yang
    sudah ada), nama, dan label "TERVERIFIKASI" bergaya sistem.

    Props:
      nama   — nama klien (disamarkan demi privasi).
      teks   — isi testimoni.
      rating — jumlah bintang 1-5.
--}}
@props([
    'nama' => '',
    'teks' => '',
    'rating' => 5,
])

<x-jd.kartu class="flex h-full flex-col p-6">
    <span aria-hidden="true" class="font-display text-4xl font-bold leading-none text-aksen">&ldquo;</span>

    <blockquote class="mt-3 flex-1 text-sm leading-relaxed text-redup">
        {{ $teks }}
    </blockquote>

    <footer class="mt-6 flex items-center gap-3 border-t border-garis pt-4">
        <span aria-hidden="true" class="text-sm tracking-tight text-amber-400">
            @for ($i = 0; $i < min($rating, 5); $i++)★@endfor
            <span class="text-garis">
                @for ($i = $rating; $i < 5; $i++)★@endfor
            </span>
        </span>
        <span class="font-mono text-[11px] uppercase tracking-[0.14em] text-tinta">{{ $nama }}</span>
        <span class="ml-auto font-mono text-[10px] uppercase tracking-[0.14em] text-redup">TERVERIFIKASI</span>
    </footer>
</x-jd.kartu>
