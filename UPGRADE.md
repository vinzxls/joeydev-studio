# Rencana Upgrade — JoeyDev Studio

Dokumen ini adalah titik lanjut untuk sesi berikutnya. Salin bagian
**"Prompt untuk sesi baru"** di bawah sebagai pesan pertama.

---

## Status sistem saat ini

- **Live:** https://julyofus.space (Hostinger Business, server Singapura, PHP 8.3)
- **Stack:** Laravel 12 · MySQL · Blade · Tailwind CSS 4 (Vite)
- **Repo:** `github.com/vinzxls/joeydev-studio`
- **Deploy:** SSH ke server → `cd ~/domains/julyofus.space/app && git pull && bash deploy.sh`
- **Admin:** `/admin` — kelola order & status, subject & harga dasar, multiplier
  & add-on, moderasi review, arsip order
- **Klien tanpa akun:** tracking lewat kode order (`JD-2026-XXXX`) + verifikasi WA/email

### Yang sudah jalan

Form order dengan estimasi harga real-time (AJAX), upload lampiran, tracking
progress dengan timeline, rating & review termoderasi, arsip order yang bisa
dipulihkan, tombol WhatsApp, splash 10 bahasa, pita teknologi & testimoni
berjalan, animasi masuk di section Cara Pesan.

Sudah dipakai klien sungguhan dengan beberapa order selesai.

---

## Kandidat upgrade (belum dikerjakan)

### 1. Notifikasi order masuk — risiko tertinggi

Saat ini **tidak ada notifikasi sama sekali**. Order baru hanya terlihat kalau
dashboard admin dibuka manual, sehingga pesanan bisa mengendap berhari-hari
sementara klien merasa diabaikan.

Rencana yang sudah dibahas:
- **Bot Telegram ke admin** untuk order masuk — gratis, instan, API resmi.
  Setup: chat `@BotFather` → `/newbot` → dapat token + chat ID.
- **Email ke klien** saat status order berubah — gratis, pakai email Hostinger
  (`noreply@julyofus.space`).
- WhatsApp lewat Fonnte (±Rp65rb/bln) ditunda sampai order rutin.

Catatan teknis: shared hosting tidak punya worker permanen. Kirim secara
langsung (sync) dengan penanganan error — **kegagalan notifikasi tidak boleh
membuat order gagal tersimpan**.

### 2. Penilaian skala otomatis

Klien memilih sendiri Kecil/Sedang/Besar dan cenderung memilih yang termurah.
Pernah terjadi: order berskala besar masuk dengan pilihan "Kecil / Dasar"
sehingga harganya jauh di bawah beban kerja sebenarnya.

Rencana bertingkat:
- **Tahap A (gratis):** ganti pilihan abstrak dengan pertanyaan faktual —
  berapa metode yang dibandingkan? perkiraan jumlah data? butuh deep learning?
  berapa halaman/fitur? Sistem yang menghitung skalanya.
- **Tahap B (±Rp50/order):** deskripsi dibaca Claude Haiku 4.5 untuk menilai
  skala + kompleksitas beserta alasannya; admin tetap memegang keputusan akhir.

Catatan jujur: otomatisasi tidak menghapus celah — klien bisa menulis deskripsi
minimalis lalu menambah permintaan lewat WhatsApp. Yang berubah, manipulasi jadi
lebih sulit dan lebih kentara.

### 3. Badge review menunggu moderasi di sidebar admin

Review baru sengaja disembunyikan sampai disetujui admin. Penandanya sekarang
ada di kartu Ringkasan paling bawah dashboard — mudah terlewat, dan pernah
membuat ulasan klien tidak tayang berhari-hari tanpa disadari.

Solusi: badge angka di menu Review pada sidebar, seperti badge Arsip.

### 4. Backup mandiri

Backup bawaan Hostinger tidak bisa diverifikasi dari luar hPanel. Yang berisiko
hilang hanya database + folder lampiran (di bawah 1 MB) — kode sudah aman di
GitHub. Rencana: script dump harian via cron Hostinger, simpan 14 hari terakhir.

### 5. Field deadline di form admin

Deadline order sekarang hanya bisa diubah lewat query database. Perlu field-nya
di halaman detail order.

### 6. Payment gateway Midtrans

Fase 3 di PRD. Struktur data (`payment_status`, `order_code`) sudah disiapkan.
Belum mendesak selama volume order masih kecil.

---

## Prompt untuk sesi baru

```
Saya lanjut mengembangkan JoeyDev Studio — platform pemesanan jasa software saya.

KONTEKS
- Laravel 12 + MySQL + Blade + Tailwind 4, live di https://julyofus.space
  (Hostinger Business, PHP 8.3). Repo: github.com/vinzxls/joeydev-studio
- Deploy: ssh ke server, lalu
  cd ~/domains/julyofus.space/app && git pull && bash deploy.sh
- Klien memesan tanpa akun, tracking lewat kode order + verifikasi WA/email
- Admin di /admin: kelola order & status, subject & harga, moderasi review, arsip
- Sudah dipakai klien sungguhan dengan beberapa order selesai
- Baca UPGRADE.md di root project untuk daftar lengkap kandidat upgrade

YANG SAYA MINTA SEKARANG
Buat RENCANA dulu, jangan langsung menulis kode. Untuk item yang saya sebut
di bawah, jelaskan:
1. File apa saja yang berubah dan garis besar perubahannya
2. Apa yang harus saya siapkan sendiri (akun, token, konfigurasi di panel)
3. Perkiraan biaya kalau ada
4. Apa yang bisa rusak atau berisiko, dan bagaimana menghindarinya
5. Urutan pengerjaan kalau butuh beberapa tahap

Tunggu persetujuan saya sebelum mulai mengubah kode.

MULAI DENGAN: <isi sendiri — misalnya "notifikasi Telegram untuk order masuk">
```

---
