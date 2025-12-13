FROM php:8.2-fpm-alpine

# Install system dependencies + PHP build tools
RUN apk update && apk add --no-cache \
    git unzip zip curl bash nodejs npm sqlite sqlite-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev pkgconf \
    autoconf g++ make

# Configure GD for PHP
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy composer files first and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the rest of the app
COPY . .

# Setup storage, cache, and database
RUN mkdir -p storage bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

# Install frontend dependencies and build
RUN npm install && npm run build

EXPOSE 80
CMD ["php-fpm", "-F"]
