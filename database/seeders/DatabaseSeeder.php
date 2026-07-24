<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\PricingMultiplier;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Admin ----
        User::updateOrCreate(
            ['email' => 'admin@joeydev.test'],
            ['name' => 'Admin JoeyDev', 'password' => Hash::make('password')],
        );

        // ---- Subjects (kategori layanan) ----
        $subjects = [
            ['name' => 'Website / Sistem Informasi', 'slug' => 'website-sistem-informasi', 'base_price' => 100000, 'unit' => 'per project', 'description' => 'Pembuatan website, sistem informasi (Laravel dsb.), CRUD, dashboard, landing page untuk kebutuhan bisnis atau pribadi Anda.'],
            ['name' => 'Aplikasi Mobile', 'slug' => 'aplikasi-mobile', 'base_price' => 100000, 'unit' => 'per project', 'description' => 'Aplikasi Android/Flutter, integrasi API, aplikasi bisnis atau prototipe produk.'],
            ['name' => 'Machine Learning / Data Science', 'slug' => 'machine-learning-data-science', 'base_price' => 100000, 'unit' => 'per project', 'description' => 'Model prediksi, analisis data, dashboard visualisasi, notebook Jupyter untuk kebutuhan bisnis dan riset Anda.'],
            ['name' => 'IoT (Internet of Things)', 'slug' => 'iot', 'base_price' => 130000, 'unit' => 'per project', 'description' => 'Prototipe ESP32/Arduino, integrasi sensor, MQTT, dashboard monitoring perangkat.'],
            ['name' => 'Jaringan & Keamanan', 'slug' => 'jaringan-keamanan', 'base_price' => 100000, 'unit' => 'per project', 'description' => 'Konfigurasi jaringan, setup server, hardening & audit keamanan untuk sistem yang Anda miliki/kelola.'],
            ['name' => 'Konsultasi & Mentoring Pemrograman', 'slug' => 'konsultasi-mentoring', 'base_price' => 80000, 'unit' => 'per sesi', 'description' => 'Sesi bimbingan belajar coding, code review, debugging bersama, dan konsultasi arsitektur project Anda.'],
            ['name' => 'Dokumen & Presentasi Teknis', 'slug' => 'dokumen-presentasi', 'base_price' => 50000, 'unit' => 'per dokumen', 'description' => 'Dokumentasi teknis produk, manual pengguna, company profile, dan slide presentasi profesional.'],
            ['name' => 'Lainnya / Custom', 'slug' => 'lainnya-custom', 'base_price' => null, 'unit' => 'konsultasi', 'is_custom' => true, 'description' => 'Kebutuhan di luar kategori di atas — harga dikonfirmasi setelah konsultasi singkat.'],
        ];

        foreach ($subjects as $i => $s) {
            Subject::updateOrCreate(['slug' => $s['slug']], $s + ['sort_order' => $i + 1]);
        }

        // ---- Pricing multipliers ----
        $multipliers = [
            ['type' => 'scale', 'label' => 'Kecil', 'description' => 'Sedikit fitur/halaman, lingkup sederhana', 'value' => 1.00, 'sort_order' => 1],
            ['type' => 'scale', 'label' => 'Sedang', 'description' => 'Beberapa modul/fitur, lingkup menengah', 'value' => 1.30, 'sort_order' => 2],
            ['type' => 'scale', 'label' => 'Besar', 'description' => 'Banyak modul, multi-role, lingkup luas', 'value' => 1.80, 'sort_order' => 3],
            ['type' => 'complexity', 'label' => 'Dasar', 'description' => 'CRUD standar, teknologi umum', 'value' => 1.00, 'sort_order' => 1],
            ['type' => 'complexity', 'label' => 'Menengah', 'description' => 'Integrasi API, logika bisnis khusus', 'value' => 1.20, 'sort_order' => 2],
            ['type' => 'complexity', 'label' => 'Lanjutan', 'description' => 'Algoritma kompleks, realtime, ML, arsitektur khusus', 'value' => 1.50, 'sort_order' => 3],
            ['type' => 'urgency', 'label' => 'Normal', 'description' => '≥ 5 hari', 'value' => 1.00, 'sort_order' => 1],
            ['type' => 'urgency', 'label' => 'Cepat', 'description' => '2–4 hari', 'value' => 1.15, 'sort_order' => 2],
            ['type' => 'urgency', 'label' => 'Mendesak', 'description' => '< 48 jam', 'value' => 1.30, 'sort_order' => 3],
        ];

        foreach ($multipliers as $m) {
            PricingMultiplier::updateOrCreate(
                ['type' => $m['type'], 'label' => $m['label']],
                $m,
            );
        }

        // ---- Add-ons ----
        $addons = [
            ['name' => 'Dokumentasi teknis', 'description' => 'Dokumen penjelasan arsitektur & cara pakai', 'price' => 50000, 'sort_order' => 1],
            ['name' => 'Slide presentasi', 'description' => 'PPT presentasi hasil project', 'price' => 40000, 'sort_order' => 2],
            ['name' => 'Sesi walkthrough kode', 'description' => 'Sesi penjelasan kode via meeting', 'price' => 50000, 'sort_order' => 3],
            ['name' => 'Setup deploy / hosting', 'description' => 'Deploy ke server/hosting milik Anda', 'price' => 75000, 'sort_order' => 4],
        ];

        foreach ($addons as $a) {
            Addon::updateOrCreate(['name' => $a['name']], $a);
        }
    }
}
