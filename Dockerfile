# Use official PHP 8.2 FPM image based on Debian
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl bash nodejs npm sqlite3 libsqlite3-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    build-essential pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the rest of the application
COPY . .

# Setup storage, cache, and database
RUN mkdir -p storage bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

# Install Node dependencies and build frontend
RUN npm install && npm run build

# Expose port 80 and start PHP-FPM
EXPOSE 80
CMD ["php-fpm"]
