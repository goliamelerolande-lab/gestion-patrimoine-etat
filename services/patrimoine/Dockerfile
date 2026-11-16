FROM php:8.4-apache

# 1. Installation des extensions PHP nécessaires (PostgreSQL + mbstring)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql mbstring

# 2. Activation du module de réécriture d'Apache
RUN a2enmod rewrite

# 3. Autoriser le .htaccess de Laravel (indispensable pour les routes)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 4. Pointer Apache vers le dossier public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 6. Copier les fichiers de dépendances EN PREMIER (pour le cache Docker)
COPY composer.json composer.lock ./

# 7. Installer les dépendances (c'est ICI que le dossier vendor est créé)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# 8. VÉRIFICATION CRUCIALE : Si vendor/autoload.php n'existe pas, le build échoue immédiatement
RUN test -f /var/www/vendor/autoload.php || (echo "ERREUR FATALE: vendor/autoload.php n'a pas été généré par composer install" && exit 1)

# 9. Copier le reste du code de l'application
COPY . .

# ... (tes lignes précédentes)

# Copier le reste du code
COPY . .

# NOUVEAU : Créer le fichier .env s'il n'existe pas et générer la clé
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN php artisan key:generate --force

# NOUVEAU : Mettre en cache la configuration (maintenant que tout est là)
RUN php artisan config:cache

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 10. Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache