# Use official PHP 8.4 FPM image based on Debian
FROM php:8.4-fpm

# Install system dependencies including Nginx
RUN apt-get update && apt-get install -y \
    git unzip zip curl bash nodejs npm sqlite3 libsqlite3-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    build-essential pkg-config libonig-dev nginx \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite zip bcmath mbstring exif pcntl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy composer files first and install dependencies (skip scripts since artisan isn't available yet)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy the rest of the application
COPY . .

# Make artisan executable and run composer scripts that require artisan
RUN chmod +x artisan && composer dump-autoload --optimize --no-interaction

# Setup storage, cache, and database
RUN mkdir -p storage bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

# Install Node dependencies and build frontend
RUN npm install && npm run build

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Make entrypoint script executable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port (Railway will set PORT env var)
EXPOSE 8000

# Use entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
