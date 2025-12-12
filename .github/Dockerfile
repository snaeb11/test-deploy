# Use PHP 8.4 CLI image
FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl sqlite3 libsqlite3-dev \
    nodejs npm

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-interaction

# Install Node dependencies & build assets
RUN npm install
RUN npm run build

# Expose Laravel port
EXPOSE 8000

# Set entrypoint to serve Laravel app
CMD php artisan serve --host=0.0.0.0 --port=8000
