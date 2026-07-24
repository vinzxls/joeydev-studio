# JoeyDev Studio — Sistem Pemesanan Jasa Software & Project IT

Platform pemesanan jasa pembuatan software (website, aplikasi mobile, ML/data science, IoT, jaringan, mentoring pemrograman, dokumen teknis) dengan estimasi harga otomatis, tracking progress tanpa akun, dan rating/testimoni.

Dibangun dengan **Laravel 12 + MySQL + Blade + Tailwind CSS 4 (Vite)**.

## Fitur

- **Landing page** — daftar layanan, statistik, testimoni klien (nama disamarkan demi privasi)
- **Form order** — pilih layanan, isi kebutuhan, upload lampiran (maks 5 file @ 10 MB), estimasi harga **real-time** via AJAX
- **Kalkulasi harga**: `Harga Dasar × Skala × Kompleksitas × Urgensi + Add-on` (tanpa cap atas; semua angka bisa diatur admin)
- **Layanan custom** — harga dikonfirmasi manual admin setelah konsultasi
- **Cek progress tanpa akun** — kode order unik (`JD-2026-XXXX`) + verifikasi WA/email, timeline visual 4 tahap
- **Rating & review** — muncul setelah order selesai; dimoderasi admin (tampil/sembunyi, isi tidak bisa diedit)
- **Dashboard admin** — statistik order/pendapatan/rating, kelola order & status (dengan riwayat), subject & harga dasar, multiplier & add-on, moderasi review
- **Keamanan** — rate limiting form order/tracking/login, validasi tipe & ukuran upload, snapshot harga per order

## Menjalankan (Laragon / lokal)

```bash
composer install
npm install && npm run build
copy .env.example .env   # lalu sesuaikan DB_* (default: joeydev / root tanpa password)
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve --port=8123
```

Buka `http://localhost:8123`.

## Login Admin

| | |
|---|---|
| URL | `/admin/login` |
| Email | `admin@joeydev.test` |
| Password | `password` |

> **Ganti password ini sebelum deploy!** Cara cepat: `php artisan tinker` lalu
> `User::first()->update(['password' => Hash::make('password-baru')])`

## Struktur Penting

| Path | Isi |
|---|---|
| `app/Services/PriceCalculator.php` | Logika kalkulasi harga |
| `app/Http/Controllers/` | Controller publik (Home, Order, Tracking, Review) |
| `app/Http/Controllers/Admin/` | Controller admin |
| `database/seeders/DatabaseSeeder.php` | Seed subject, multiplier, add-on, admin |
| `resources/views/` | Blade views (layouts, home, order, tracking, admin) |

## Rencana Fase Berikutnya (dari PRD)

- Notifikasi WhatsApp otomatis (Fonnte/Wablas) saat order masuk & status berubah
- Payment gateway Midtrans Snap (DP/pelunasan otomatis via webhook)
- Estimasi harga berbantuan AI dari deskripsi teks (dengan verifikasi admin)
