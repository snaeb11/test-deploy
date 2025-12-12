#!/bin/bash
set -e

# Ensure SQLite database exists
DB_FILE=/app/database/database.sqlite
if [ ! -f "$DB_FILE" ]; then
    mkdir -p /app/database
    touch "$DB_FILE"
    chmod -R 777 /app/database
fi

# Ensure storage and cache directories are writable
chmod -R 777 /app/storage /app/bootstrap/cache

# Clear caches and run migrations
php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Get Railway-assigned port (fallback to 8000)
PORT=${PORT:-8000}

# Replace Nginx listen port dynamically
sed -i "s/listen 8000;/listen ${PORT};/" /etc/nginx/sites-available/default

# Start PHP-FPM
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
