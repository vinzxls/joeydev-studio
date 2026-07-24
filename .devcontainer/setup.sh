#!/usr/bin/env bash
# Setup otomatis JoeyDev Studio di GitHub Codespaces (pakai SQLite, tanpa MySQL)
set -e

echo "==> Install dependensi PHP..."
composer install --no-interaction --prefer-dist

echo "==> Install & build asset frontend..."
npm ci
npm run build

echo "==> Konfigurasi environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

echo "==> Siapkan database SQLite..."
touch database/database.sqlite
php artisan migrate --seed --force
php artisan storage:link || true

echo ""
echo "=========================================="
echo "  Setup selesai!"
echo "  Jalankan:  php artisan serve"
echo "  Lalu di panel PORTS: klik kanan port 8000"
echo "  -> Port Visibility -> Public"
echo "  Login admin: admin@joeydev.test / password"
echo "=========================================="
