FROM php:8.4-cli

ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    sqlite3 libsqlite3-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    nodejs npm \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions (GD MUST be configured before install)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        pdo_sqlite

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy composer files first
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy application
COPY . .

# Frontend build
RUN npm install && npm run build

# Laravel directories
RUN mkdir -p database storage bootstrap/cache \
    && chmod -R 777 database storage bootstrap/cache

# SQLite file (safe even if unused)
RUN touch database/database.sqlite

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
