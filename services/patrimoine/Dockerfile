FROM php:8.4-apache

# Installation des extensions PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql mbstring

# Activation du module de réécriture d'Apache
RUN a2enmod rewrite

# Autoriser le .htaccess de Laravel
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Pointer Apache vers le dossier public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier UNIQUEMENT les fichiers de dépendances d'abord (pour le cache Docker)
COPY composer.json composer.lock ./

# Installer les dépendances (C'EST CETTE ÉTAPE QUI CRÉE LE DOSSIER VENDOR)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copier le reste du code
COPY . .

# Générer l'autoloader optimisé
RUN composer dump-autoload --optimize

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache