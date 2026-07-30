# Panduan Deploy — Hostinger (julyofus.space)

Panduan langkah demi langkah men-deploy JoeyDev Studio ke shared hosting Hostinger
dengan domain `julyofus.space`. Hasil build asset (`public/build`) sudah termasuk
di repo, jadi server **tidak perlu Node.js** — cukup PHP 8.2+ dan MySQL.

---

## 1. Hubungkan domain ke hosting

1. Login **hPanel** → **Websites** → **Add Website** (atau **Website → Dasbor** jika sudah ada)
2. Pilih domain **julyofus.space** → ikuti wizard sampai website dibuat
3. Pastikan **PHP 8.2 atau 8.3**: hPanel → website Anda → **Advanced → PHP Configuration**

## 2. Buat database MySQL

1. hPanel → **Databases → Management**
2. Buat database baru — catat 3 hal: **nama database**, **username**, **password**
   (format Hostinger biasanya `u123456_namadb` / `u123456_user`)

## 3. Upload kode (via SSH — paket Premium ke atas)

Aktifkan SSH di hPanel → **Advanced → SSH Access**, lalu dari terminal:

```bash
ssh u123456@ip-server -p 22

# masuk ke folder domain
cd ~/domains/julyofus.space

# clone repo (repo private: pakai personal access token GitHub saat diminta password)
git clone https://github.com/vinzxls/joeydev-studio.git app
cd app

# install dependensi PHP (tanpa dev)
composer install --no-dev --optimize-autoloader
```

> **Tanpa SSH?** (paket Single) — jalankan `composer install --no-dev` di lokal,
> zip seluruh folder project **termasuk `vendor/`**, upload via File Manager,
> lalu extract ke `~/domains/julyofus.space/app`.

## 4. Konfigurasi `.env` produksi

```bash
cp .env.example .env
nano .env
```

Ubah nilai-nilai ini:

```ini
APP_ENV=production
APP_DEBUG=false            # WAJIB false — jika true, error membocorkan kredensial
APP_URL=https://julyofus.space

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u123456_joeydev   # sesuaikan dari langkah 2
DB_USERNAME=u123456_user
DB_PASSWORD=passwordDatabaseAnda
```

Lalu:

```bash
php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## 5. Arahkan document root ke folder `public`

Laravel harus disajikan dari folder `public/`, bukan root project:

```bash
cd ~/domains/julyofus.space
rm -rf public_html
ln -s app/public public_html
```

## 6. Aktifkan SSL

hPanel → website Anda → **Security → SSL** → Install (gratis, biasanya otomatis).
Setelah aktif, nyalakan juga **Force HTTPS**.

## 7. Ganti password admin (WAJIB)

```bash
php artisan tinker
>>> App\Models\User::first()->update(['password' => Hash::make('password-baru-yang-kuat')]);
>>> exit
```

Sekalian ganti emailnya jika mau (default: `admin@joeydev.test`).

## 8. Uji akhir

- Buka `https://julyofus.space` — landing tampil dengan styling penuh
- Buat 1 order percobaan dari HP → cek masuk di `/admin`
- Hapus order percobaan dari database jika perlu

---

## Update kode di kemudian hari

```bash
cd ~/domains/julyofus.space/app
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Menambah project lain sebagai subdomain (mis. halsel.julyofus.space)

1. hPanel → **Domains → Subdomains** → buat `halsel`
2. Clone project-nya ke `~/domains/julyofus.space/halsel-app` (atau folder lain)
3. Arahkan document root subdomain ke folder `public` project itu (symlink seperti langkah 5)
4. Ulangi langkah `.env` + migrate + SSL
