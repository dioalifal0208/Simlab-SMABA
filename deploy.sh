#!/bin/bash

git pull origin main
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan config:clear
php artisan cache:clear
php artisan view:clear
