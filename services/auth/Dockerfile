FROM php:8.4-fpm-alpine

RUN apk add --no-cache bash git unzip libpq-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --no-scripts --no-autoloader \
    && composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80