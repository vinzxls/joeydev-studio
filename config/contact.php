<?php

return [
    /*
     * Nomor WhatsApp bisnis untuk tombol kontak di halaman publik.
     * Format internasional tanpa tanda plus, mis: 6282112345678.
     * Jika dikosongkan, tombol WhatsApp tidak ditampilkan.
     */
    'whatsapp' => env('WHATSAPP_NUMBER'),

    /*
     * Pesan yang otomatis terisi saat pengunjung menekan tombol WhatsApp.
     */
    'whatsapp_message' => env(
        'WHATSAPP_MESSAGE',
        'Halo, JoeyDev Studio. Saya ingin bertanya tentang jasa pembuatan software.',
    ),
];
