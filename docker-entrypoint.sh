#!/bin/bash
set -e

echo "Starting Laravel application setup..."

# Ensure SQLite database exists
DB_FILE=/app/database/database.sqlite
if [ ! -f "$DB_FILE" ]; then
    echo "Creating SQLite database..."
    mkdir -p /app/database
    touch "$DB_FILE"
    chmod -R 777 /app/database
fi

# Ensure storage/cache are writable
echo "Setting up storage permissions..."
chmod -R 777 /app/storage /app/bootstrap/cache

# Clear any cached config first (important for key generation)
echo "Clearing cached configuration..."
php artisan config:clear || true
php artisan cache:clear || true

# Check if APP_KEY is set (required for Laravel)
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is not set. Generating one..."
    # Ensure .env file exists
    if [ ! -f /app/.env ]; then
        echo "Creating .env file..."
        if [ -f /app/.env.example ]; then
            cp /app/.env.example /app/.env
        else
            # Create minimal .env file
            cat > /app/.env <<EOF
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

LOG_CHANNEL=stack
LOG_LEVEL=error
EOF
        fi
        chmod 666 /app/.env
    fi
    # Generate the key (this will write to .env)
    php artisan key:generate --force || {
        echo "ERROR: Failed to generate APP_KEY"
        exit 1
    }
    # Read the generated key from .env and export it
    APP_KEY_VALUE=$(grep "^APP_KEY=" /app/.env | cut -d '=' -f2- | sed 's/^[[:space:]]*//;s/[[:space:]]*$//')
    if [ -n "$APP_KEY_VALUE" ] && [ "$APP_KEY_VALUE" != "base64:" ]; then
        export APP_KEY="$APP_KEY_VALUE"
        echo "Successfully generated APP_KEY"
    else
        echo "ERROR: APP_KEY was not properly written to .env file"
        echo "Contents of .env APP_KEY line:"
        grep "^APP_KEY=" /app/.env || echo "APP_KEY line not found in .env"
        exit 1
    fi
else
    echo "APP_KEY is already set from environment."
    # Also ensure it's in .env file for Laravel to read
    if [ ! -f /app/.env ] || ! grep -q "^APP_KEY=" /app/.env; then
        echo "Adding APP_KEY to .env file..."
        if [ ! -f /app/.env ]; then
            touch /app/.env
            chmod 666 /app/.env
        fi
        echo "APP_KEY=$APP_KEY" >> /app/.env
    fi
fi

# Clear config cache again after key generation to ensure Laravel reads the new key
echo "Clearing config cache to ensure APP_KEY is loaded..."
php artisan config:clear || true

# Run migrations
echo "Running database migrations..."
if php artisan migrate --force; then
    echo "Migrations completed successfully."
else
    echo "WARNING: Migrations failed, but continuing..."
fi

# Cache configuration for better performance (only after migrations)
echo "Caching Laravel configuration..."
# Clear first to ensure fresh cache
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Then cache
php artisan config:cache || {
    echo "WARNING: Config cache failed, will use uncached config..."
}

php artisan route:cache || {
    echo "WARNING: Route cache failed, will use uncached routes..."
}

php artisan view:cache || {
    echo "WARNING: View cache failed, will use uncached views..."
}

# Replace Nginx port with Railway's $PORT
PORT=${PORT:-8000}
echo "Configuring Nginx to listen on port $PORT..."
sed -i "s/listen 8000;/listen ${PORT};/" /etc/nginx/sites-available/default

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D

# Wait a moment for PHP-FPM to start
sleep 2
echo "PHP-FPM started in background."

# Start Nginx in foreground
echo "Starting Nginx on port $PORT..."
nginx -g "daemon off;"
