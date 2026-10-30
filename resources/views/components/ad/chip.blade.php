{{--
    Chip status admin.

    Warna mengikuti makna, bukan sekadar dekorasi:
      baru    — netral, belum disentuh
      proses  — biru aksen, sedang jalan
      revisi  — kuning, menunggu tindakan klien
      selesai — hijau, tuntas
      batal   — merah

    Props:
      jenis  — 'status' (default) atau 'bayar'.
      nilai  — nilai mentah dari database (mis. 'dikerjakan', 'lunas').
--}}
@props([
    'jenis' => 'status',
    'nilai' => '',
])

@php
    $petaStatus = [
        'menunggu_konfirmasi' => 'baru',
        'dikerjakan' => 'proses',
        'review_revisi' => 'revisi',
        'selesai' => 'selesai',
        'dibatalkan' => 'batal',
    ];

    $petaBayar = [
        'belum_bayar' => 'baru',
        'dp' => 'revisi',
        'lunas' => 'selesai',
    ];

    $peta = $jenis === 'bayar' ? $petaBayar : $petaStatus;
    $kelas = $peta[$nilai] ?? 'baru';

    $label = $jenis === 'bayar'
        ? (\App\Models\Order::PAYMENT_STATUSES[$nilai] ?? $nilai)
        : (\App\Models\Order::STATUSES[$nilai] ?? $nilai);
@endphp

<span {{ $attributes->class('ad-chip '.$kelas) }}>{{ $label }}</span>
