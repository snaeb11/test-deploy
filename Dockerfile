FROM richarvey/nginx-php-fpm:latest

# Enable composer as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Laravel environment
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Nginx / Laravel config
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Copy app
COPY . /var/www/html

# Install Node (for Vite / Laravel Mix)
RUN apk add --no-cache nodejs npm

# Install frontend assets
RUN npm install && npm run build

CMD ["/start.sh"]
