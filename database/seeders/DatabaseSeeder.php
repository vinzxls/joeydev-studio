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
            ['name' => 'Desain UI/UX', 'slug' => 'desain-ui-ux', 'base_price' => 80000, 'unit' => 'per project', 'description' => 'Mockup & prototipe Figma, redesign tampilan website/aplikasi, perbaikan tata letak agar lebih rapi dan nyaman digunakan.'],
            ['name' => 'Dokumen & Presentasi Teknis', 'slug' => 'dokumen-presentasi', 'base_price' => 50000, 'unit' => 'per dokumen', 'description' => 'Dokumentasi teknis produk, manual pengguna, company profile, dan slide presentasi profesional.'],
            ['name' => 'Lainnya / Custom', 'slug' => 'lainnya-custom', 'base_price' => null, 'unit' => 'konsultasi', 'is_custom' => true, 'description' => 'Kebutuhan di luar kategori di atas — harga dikonfirmasi setelah konsultasi singkat.'],
        ];

        foreach ($subjects as $i => $s) {
            Subject::updateOrCreate(['slug' => $s['slug']], $s + ['sort_order' => $i + 1]);
        }

        // ---- Pricing multipliers ----
        $multipliers = [
            ['type' => 'scale', 'label' => 'Kecil', 'description' => '1–3 halaman/fitur, 1 metode, dataset kecil', 'value' => 1.00, 'sort_order' => 1],
            ['type' => 'scale', 'label' => 'Sedang', 'description' => '4–8 halaman/fitur, beberapa modul, atau analisis 1 metode lengkap dengan evaluasi', 'value' => 1.30, 'sort_order' => 2],
            ['type' => 'scale', 'label' => 'Besar', 'description' => '>8 fitur, multi-role, dataset besar, atau perbandingan 2+ metode', 'value' => 1.80, 'sort_order' => 3],
            ['type' => 'complexity', 'label' => 'Dasar', 'description' => 'CRUD standar, script sederhana, banyak rujukan tersedia', 'value' => 1.00, 'sort_order' => 1],
            ['type' => 'complexity', 'label' => 'Menengah', 'description' => 'Integrasi API, logika khusus, preprocessing & evaluasi data', 'value' => 1.20, 'sort_order' => 2],
            ['type' => 'complexity', 'label' => 'Lanjutan', 'description' => 'Deep learning, computer vision, realtime, arsitektur khusus', 'value' => 1.50, 'sort_order' => 3],
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
