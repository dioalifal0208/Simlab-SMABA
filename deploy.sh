#!/bin/bash
set -e

echo "================================="
echo "STARTING DEPLOYMENT"
echo "================================="
cd ~/domains/smanegeri1babatlmg.sch.id/public_html/lab || exit 1

echo "Current directory:"
pwd

# ========================
# Enable maintenance mode
# ========================
echo "Enabling maintenance mode..."
php artisan down || true

# ========================
# Pull latest code
# ========================
echo "Pulling latest code..."
git pull origin main

# ========================
# Install PHP dependencies
# ========================
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# ========================
# Node build (optional)
# ========================
echo "Checking Node availability..."
if command -v npm &> /dev/null
then
    echo "Node detected. Running build..."
    npm install
    npm run build
else
    echo "Node NOT available. Skipping build (using committed assets)"
fi

# ========================
# Run database migration
# ========================
echo "Running migrations..."
php artisan migrate --force

# ========================
# Storage symlink
# (Menggunakan ln manual karena exec() disabled di shared hosting Hostinger)
# ========================
echo "Creating storage symlink..."
ln -sfn $(pwd)/storage/app/public $(pwd)/public/storage

# ========================
# Optimize Laravel
# ========================
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ========================
# Fix permissions
# ========================
echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache

# ========================
# Disable maintenance mode
# ========================
echo "Disabling maintenance mode..."
php artisan up

echo "================================="
echo "DEPLOYMENT SUCCESS"
echo "================================="