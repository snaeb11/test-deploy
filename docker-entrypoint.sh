#!/bin/bash
set -e

# Ensure SQLite database exists
DB_FILE=/app/database/database.sqlite
if [ ! -f "$DB_FILE" ]; then
    mkdir -p /app/database
    touch "$DB_FILE"
    chmod 777 "$DB_FILE"
fi

# Clear Laravel caches and run migrations
php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Use Railway-assigned port or fallback to 8000
PORT=${PORT:-8000}

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=$PORT
