FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl sqlite3 libsqlite3-dev \
    nodejs npm

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-interaction

# Build assets
RUN npm install && npm run build

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
