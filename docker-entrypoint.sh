#!/bin/bash
set -e

# Ensure SQLite database exists
DB_FILE=/app/database/database.sqlite
if [ ! -f "$DB_FILE" ]; then
    mkdir -p /app/database
    touch "$DB_FILE"
    chmod 777 "$DB_FILE"
fi

# Clear config cache and run migrations
php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=8000
