# Use official PHP-FPM Alpine image
FROM php:8.2-fpm-alpine

# Install system dependencies + PHP extensions
RUN apk update && apk add --no-cache \
    git unzip zip curl bash nodejs npm sqlite sqlite-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (cache layer)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy application files
COPY . .

# Ensure SQLite database exists
RUN mkdir -p database storage bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

# Install frontend dependencies and build assets
RUN npm install && npm run build

# Expose port
EXPOSE 80

# Run PHP-FPM in foreground
CMD ["php-fpm", "-F"]
