#!/bin/bash
# Script otomatis untuk update website di server Hostinger

echo "🚀 Memulai proses update..."

# Masuk ke direktori
cd ~/Simlab-SMABA

# 1. Ambil kode terbaru dari GitHub
echo "📥 Menarik kode terbaru (Git Pull)..."
git pull origin main

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
echo "⚠️  PENTING: Jika Anda mengubah file CSS/JS (Tailwind), pastikan Anda sudah menjalankan 'npm run build' di komputer lokal dan meng-upload folder 'public/build' ke server."
