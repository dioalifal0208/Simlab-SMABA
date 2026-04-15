#!/bin/bash
# Script otomatis untuk update website di server Hostinger

echo "🚀 Memulai proses update..."

# Masuk ke direktori
cd ~/domains/websmaba.site/public_html

# 1. Pastikan file .env ada
if [ ! -f ".env" ]; then
    echo "⚠️  File .env tidak ditemukan! Pastikan Anda sudah menguploadnya."
    exit 1
fi

# 2. Install dependency PHP (jika ada perubahan di composer.json)
echo "📦 Menginstall dependency..."
composer install --no-dev --optimize-autoloader

# 3. Jalankan migration database (jika ada perubahan database)
echo "🗄️ Menjalankan migration database..."
php artisan migrate --force

# 4. Bersihkan dan refresh cache
echo "🧹 Membersihkan cache..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Update selesai! Website sudah menggunakan versi terbaru."
echo "⚠️  PENTING: Versi CSS/JS (Tailwind) sekarang di-build otomatis oleh GitHub Actions. Anda tidak perlu lagi menjalankan npm run build di laptop."
