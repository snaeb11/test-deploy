# ----------- Stage 1: Build ----------- #
FROM php:8.4-fpm AS build

ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev \
    zip unzip git curl sqlite3 libsqlite3-dev \
    nodejs npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_sqlite zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy composer files first for caching
COPY composer.json composer.lock ./

# Install PHP dependencies without scripts
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

# Copy full project
COPY . .

# Run Laravel package discovery
RUN php artisan package:discover --ansi

# Ensure directories exist and are writable
RUN mkdir -p /app/database /app/storage /app/bootstrap/cache
RUN chmod -R 777 /app/database /app/storage /app/bootstrap/cache

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# ----------- Stage 2: Production ----------- #
FROM php:8.4-fpm

ENV DEBIAN_FRONTEND=noninteractive

# Install PHP extensions required at runtime
RUN apt-get update && apt-get install -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev \
    zip unzip sqlite3 libsqlite3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_sqlite zip

WORKDIR /app

# Copy built app from build stage
COPY --from=build /app /app

# Ensure directories exist and writable
RUN mkdir -p /app/database /app/storage /app/bootstrap/cache
RUN chmod -R 777 /app/database /app/storage /app/bootstrap/cache

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose container port (optional)
EXPOSE 8000

# Use entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
