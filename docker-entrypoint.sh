#!/bin/bash
set -e

# Ensure SQLite database exists
DB_FILE=/app/database/database.sqlite
if [ ! -f "$DB_FILE" ]; then
    mkdir -p /app/database
    touch "$DB_FILE"
    chmod -R 777 /app/database

    # Run migrations only if database is empty
    php artisan migrate --force
fi

# Ensure storage/cache are writable
chmod -R 777 /app/storage /app/bootstrap/cache

# Replace Nginx port with Railway's $PORT
PORT=${PORT:-8000}
sed -i "s/listen 8000;/listen ${PORT};/" /etc/nginx/sites-available/default

# Start PHP-FPM
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
