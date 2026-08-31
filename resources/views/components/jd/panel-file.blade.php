{{--
    Panel file — BLUEPRINT.md §5 (Konten) & §6.1 (Tentang).

    Metafora file-explorer untuk section Tentang. Sengaja statis: semua konten
    tampil sekaligus sebagai HTML semantik, BUKAN daftar file yang bisa
    diklik, dan BUKAN navigasi JS. Baris file dirender sebagai <li> polos.

    Props:
      path — label "direktori aktif", mis. ~/joeydev.
      slot — daftar entri file (masing-masing <li> dengan nama + metadata).
--}}
@props(['path' => '~/joeydev'])

<x-jd.kartu kurung class="overflow-hidden">
    <div class="flex items-center gap-2 border-b border-garis px-5 py-3">
        <span aria-hidden="true" class="flex gap-1.5">
            <span class="h-2.5 w-2.5 rounded-full bg-garis"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-garis"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-garis"></span>
        </span>
        <span class="ml-2 font-mono text-[12px] text-redup">$ {{ $path }}</span>
    </div>

    <ul class="divide-y divide-garis font-mono text-sm">
        {{ $slot }}
    </ul>
</x-jd.kartu>
