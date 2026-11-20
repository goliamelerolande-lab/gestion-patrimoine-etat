# ==============================================================================
# GEST-PATRIMOINE (République Togolaise) - Dockerfile Universel de Déploiement Cloud
# Compatible : Render, Koyeb, Railway, Fly.io, Alwaysdata, VPS
# ==============================================================================

FROM php:8.2-cli-alpine

# Installation des dépendances système et des extensions PDO (PostgreSQL & SQLite)
RUN apk add --no-cache \
    postgresql-dev \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    bash \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite

WORKDIR /app

# Copie des fichiers du projet
COPY . /app

# Configuration des permissions pour les téléversements (cachet, signatures) et le stockage SQLite
RUN mkdir -p /app/gateway-frontend/public/uploads \
    && mkdir -p /app/auth-service/storage \
    && mkdir -p /app/patrimoine-service/storage \
    && mkdir -p /app/affectation-service/storage \
    && chmod -R 777 /app/gateway-frontend/public/uploads \
    && chmod -R 777 /app/*/storage 2>/dev/null || true

ENV PORT=8080
EXPOSE 8080

# Démarrage automatique sur le port dynamique fourni par l'hébergeur cloud ($PORT)
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t gateway-frontend/public"]
