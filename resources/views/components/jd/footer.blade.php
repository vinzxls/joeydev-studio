{{--
    Footer — BLUEPRINT.md §6 (09).

    Minimal dan teknis: tiga kolom tipis, bukan footer raksasa. Perannya cuma
    metadata sistem dan jalur keluar, bukan section tambahan.
    Latar lebih gelap dari halaman (#03060c) supaya terpisah tanpa bayangan.
--}}
@php
    $wa = preg_replace('/\D/', '', (string) config('contact.whatsapp'));
    $pesanWa = rawurlencode((string) config('contact.whatsapp_message'));
@endphp

<footer class="mt-24 border-t border-garis bg-[#03060c]">
    <div class="mx-auto max-w-[1280px] px-4 py-12 md:px-8 md:py-16">
        <div class="grid gap-10 md:grid-cols-3 md:gap-8">

            {{-- Kolom 1 — identitas & status --}}
            <div>
                <p class="jd-judul text-2xl leading-none text-tinta">JD<span class="text-aksen-terang">.</span></p>
                <p class="mt-3 max-w-xs text-sm leading-relaxed text-redup">
                    Jasa pembuatan website, aplikasi, dan project IT. Dikerjakan langsung
                    oleh yang menulis kodenya.
                </p>
                <x-jd.badge-status class="mt-5">Menerima project baru</x-jd.badge-status>
            </div>

            {{-- Kolom 2 — navigasi --}}
            <div>
                <x-jd.label-section>Navigasi</x-jd.label-section>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}" class="text-redup transition-colors hover:text-tinta">Beranda</a></li>
                    <li><a href="{{ route('order.create') }}" class="text-redup transition-colors hover:text-tinta">Pesan Sekarang</a></li>
                    <li><a href="{{ route('track.form') }}" class="text-redup transition-colors hover:text-tinta">Cek Progress</a></li>
                </ul>
            </div>

            {{-- Kolom 3 — kontak --}}
            <div>
                <x-jd.label-section>Kontak</x-jd.label-section>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @if ($wa !== '')
                        <li>
                            <a href="https://wa.me/{{ $wa }}?text={{ $pesanWa }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 text-redup transition-colors hover:text-tinta">
                                WhatsApp
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     class="h-3 w-3 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0H8m9 0v9" />
                                </svg>
                            </a>
                        </li>
                    @endif
                    <li><a href="{{ route('admin.login') }}" class="text-redup transition-colors hover:text-tinta">Login Admin</a></li>
                </ul>
            </div>
        </div>

        {{-- Baris bawah --}}
        <div class="mt-12 flex flex-col gap-3 border-t border-garis pt-6 font-mono text-[11px] uppercase tracking-[0.14em] text-redup sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} JoeyDev Studio</p>
            <p aria-hidden="true">Software &middot; Project IT &middot; UI/UX</p>
        </div>
    </div>
</footer>
