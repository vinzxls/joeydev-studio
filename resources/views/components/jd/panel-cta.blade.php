{{--
    Panel CTA — BLUEPRINT.md §5 (Aksi).

    Kartu inverted (gelap) penutup; aksi utama sengaja memakai blok terang
    (bg-kertas / text-tinta) UNTUK KONTRAS di atas kartu gelap — bukan biru,
    karena biru dijatah untuk satu frasa headline, tautan, dan status (§3).

    Props:
      judul     — heading font-display besar.
      slot      — teks pendukung.
      whatsapp  — href untuk tombol utama (opsional; '' = lewati).
      pesan     — href untuk tombol sekunder (opsional; '' = lewati).
--}}
@props([
    'judul' => '',
    'whatsapp' => '',
    'pesan' => '',
])

<x-jd.kartu gelap kurung class="px-6 py-10 sm:px-10 sm:py-14 text-center">
    <h2 class="mx-auto max-w-2xl font-display text-3xl font-extrabold leading-tight sm:text-4xl">
        {{ $judul }}
    </h2>

    @if ($slot)
        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-kertas/70">
            {{ $slot }}
        </p>
    @endif

    @if ($whatsapp || $pesan)
        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            @if ($whatsapp)
                <a href="{{ $whatsapp }}"
                   class="inline-flex items-center justify-center gap-2.5 rounded-kartu bg-kertas px-6 py-3.5 font-sans text-sm font-bold text-tinta transition duration-200 hover:bg-tinta hover:text-kertas focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen">
                    {{ __('Konsultasi via WhatsApp') }}
                </a>
            @endif

            @if ($pesan)
                <a href="{{ $pesan }}"
                   class="inline-flex items-center justify-center gap-2.5 rounded-kartu border border-kertas/40 px-6 py-3.5 font-sans text-sm font-bold text-kertas transition duration-200 hover:border-kertas focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-aksen">
                    {{ __('Kirim Permintaan') }}
                </a>
            @endif
        </div>
    @endif
</x-jd.kartu>
