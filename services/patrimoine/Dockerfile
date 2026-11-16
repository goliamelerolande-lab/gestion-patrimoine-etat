# Étape 1 : Installation des dépendances (Vendor)
FROM composer:2 AS vendor
WORKDIR /app
# On copie d'abord les fichiers de dépendances pour profiter du cache Docker
COPY composer.json composer.lock ./
# Installation des dépendances de production
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Étape 2 : Image finale (Runtime)
FROM php:8.4-apache

# Installation des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql mbstring

# Activation du module de réécriture d'Apache
RUN a2enmod rewrite

# Autoriser le .htaccess de Laravel
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Pointer Apache vers le dossier public
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www

# On copie les dépendances déjà installées depuis l'étape "vendor"
COPY --from=vendor /app/vendor ./vendor

# On copie le reste du code de l'application
COPY . .

# On génère l'autoloader optimisé (maintenant que tout le code est là)
RUN composer dump-autoload --optimize

# Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache