#!/usr/bin/env bash

set -e

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "Generating storage folders..."
mkdir -p storage framework/cache framework/sessions framework/views
chmod -R 775 storage bootstrap/cache

echo "Running migrations..."
php artisan migrate --force

echo "Caching config..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
