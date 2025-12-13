FROM php:8.4-cli

ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    nodejs npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

WORKDIR /app

# Copy composer files first (better caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy application files
COPY . .

# Build frontend assets
RUN npm install && npm run build

# Ensure required directories exist
RUN mkdir -p database storage bootstrap/cache \
    && chmod -R 777 database storage bootstrap/cache

# Create SQLite DB if missing (safe even if unused)
RUN touch database/database.sqlite

# Railway uses $PORT automatically
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
