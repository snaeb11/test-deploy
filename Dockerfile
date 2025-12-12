# ----------- Stage 1: Build ----------- #
FROM php:8.4-fpm AS build

# Prevent interactive prompts during apt-get install
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    libzip-dev zip unzip git curl sqlite3 libsqlite3-dev \
    nodejs npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_sqlite zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy composer files first (for caching)
COPY composer.json composer.lock ./

# Install PHP dependencies without running scripts
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# Copy full project
COPY . .

# Run post-autoload scripts now that artisan exists
RUN composer run-script post-autoload-dump

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# ----------- Stage 2: Production ----------- #
FROM php:8.4-fpm

# Non-interactive
ENV DEBIAN_FRONTEND=noninteractive

# Install PHP extensions required at runtime
RUN apt-get update && apt-get install -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev zip unzip sqlite3 libsqlite3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_sqlite zip

# Set working directory
WORKDIR /app

# Copy built app and dependencies from build stage
COPY --from=build /app /app

# Expose Laravel default port
EXPOSE 8000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
