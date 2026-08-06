#!/usr/bin/env bash
# Script deploy/update JoeyDev Studio di server produksi (Hostinger).
# Jalankan dari root project, SETELAH file .env terisi benar.
#
#   Pertama kali : bash deploy.sh --first
#   Update rutin : bash deploy.sh
set -e

FIRST_RUN=false
[ "$1" = "--first" ] && FIRST_RUN=true

if [ ! -f .env ]; then
    echo "ERROR: file .env belum ada. Salin dari .env.example lalu isi dulu:"
    echo "  cp .env.example .env && nano .env"
    exit 1
fi

echo "==> Install dependensi PHP (tanpa dev)..."
# --no-scripts: shared hosting (Hostinger) menonaktifkan proc_open sehingga
# Composer tidak bisa memanggil artisan. Script-nya dijalankan manual di bawah.
composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

echo "==> Deteksi paket Laravel..."
php artisan package:discover

if [ "$FIRST_RUN" = true ]; then
    echo "==> Generate APP_KEY..."
    php artisan key:generate --force

    echo "==> Migrasi + data awal (subject, harga, admin)..."
    php artisan migrate --seed --force

    echo "==> Symlink storage publik..."
    php artisan storage:link || true
else
    echo "==> Migrasi database..."
    php artisan migrate --force
fi

echo "==> Optimasi cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "=================================================="
echo "  Deploy selesai."
if [ "$FIRST_RUN" = true ]; then
    echo ""
    echo "  JANGAN LUPA ganti password admin:"
    echo "    php artisan tinker"
    echo "    >>> App\\Models\\User::first()->update(['password' => Hash::make('password-baru')]);"
fi
echo "=================================================="
