FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    git unzip zip curl nodejs npm sqlite sqlite-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite zip

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction
COPY . .

RUN mkdir -p storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database

RUN npm install && npm run build

EXPOSE 80
CMD ["php-fpm"]
