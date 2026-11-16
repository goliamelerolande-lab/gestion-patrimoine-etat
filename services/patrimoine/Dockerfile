FROM php:8.4-apache

# 1. Extensions PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql mbstring

# 2. Apache
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 4. Dépendances
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# 5. Code
COPY . .

# 6. FORCER PostgreSQL dans .env (écrase tout ce qui existe)
RUN rm -f .env
RUN echo "APP_ENV=production" > .env
RUN echo "APP_DEBUG=false" >> .env
RUN echo "APP_KEY=base64:n3wgYh7ZUC9/6jBCrUxwyxpXcJjn9wuWKz1VxSzgn4M=" >> .env
RUN echo "DB_CONNECTION=pgsql" >> .env
RUN echo "SESSION_DRIVER=file" >> .env
RUN echo "CACHE_STORE=file" >> .env
RUN echo "QUEUE_CONNECTION=sync" >> .env

# 7. Nettoyer les caches et régénérer
RUN rm -f bootstrap/cache/config.php
RUN php artisan config:cache

# 8. Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache