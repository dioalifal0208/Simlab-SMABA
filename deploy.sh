#!/bin/bash

cd ~/domains/websmaba.site/public_html

echo "Pulling latest code..."
git pull origin main

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "Checking Node availability..."

if command -v npm &> /dev/null
then
    echo "Node detected. Running build..."
    npm install
    npm run build
else
    echo "Node NOT available. Skipping build (assumes build already committed)"
fi

echo "Clearing Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "Fixing permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "Deploy finished."
