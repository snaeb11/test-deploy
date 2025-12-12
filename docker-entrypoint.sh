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

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear

# Run migrations (force to avoid prompt)
php artisan migrate --force

# Start Laravel server on Railway port
PORT=${PORT:-8000}
exec php artisan serve --host=0.0.0.0 --port=$PORT
