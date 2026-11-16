# Étape 1 : Installation des dépendances
FROM composer:2.8 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# On installe les dépendances de production
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Étape 2 : Image finale
FROM php:8.4-apache

# Extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql mbstring

# Configuration Apache pour Laravel
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www

# On copie le dossier vendor généré à l'étape 1
COPY --from=vendor /app/vendor ./vendor

# On copie tout le reste de l'application
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache