FROM php:8.4-fpm

ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev \
    zip unzip git curl sqlite3 libsqlite3-dev \
    nodejs npm nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_sqlite zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy composer files first for caching
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

# Copy project files
COPY . .

# Laravel package discovery
RUN php artisan package:discover --ansi

# Ensure directories exist
RUN mkdir -p /app/database /app/storage /app/bootstrap/cache
RUN chmod -R 777 /app/database /app/storage /app/bootstrap/cache

# Node.js build
RUN npm install && npm run build

# Copy Nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Copy entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose (dummy) port, actual port is dynamic
EXPOSE 8000

ENTRYPOINT ["docker-entrypoint.sh"]
