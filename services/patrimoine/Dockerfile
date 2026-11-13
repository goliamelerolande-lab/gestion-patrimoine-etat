FROM node:24-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY resources/views ./resources/views
COPY vite.config.js ./
RUN npm run build

FROM php:8.4-apache

# Installation des extensions PHP pour PostgreSQL et mbstring
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql mbstring

# Activation du module de réécriture d'URL d'Apache
RUN a2enmod rewrite

# AUTORISER le fichier .htaccess de Laravel (Très important !)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# On dit à Apache de pointer vers le dossier /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copie du projet et permissions
COPY . /var/www
COPY --from=frontend /app/public/build /var/www/public/build
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
