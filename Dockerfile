# ===============================
# 1. BUILD STAGE
# ===============================
FROM php:8.4-fpm-alpine AS builder

WORKDIR /var/www

RUN apk update && apk upgrade

# Dépendances système (build)
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    postgresql-dev

# Dépendances runtime
RUN apk add --no-cache \
    bash \
    curl \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    oniguruma \
    postgresql-libs \
    postgresql-client \
    icu-libs

# Extensions PHP (UNE SEULE FOIS)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
    gd \
    pdo \
    pdo_pgsql \
    mbstring \
    zip \
    bcmath \
    pcntl \
    intl \
 && pecl install redis \
 && docker-php-ext-enable redis opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Code source
COPY . .

# Copy default env at build time
RUN if [ ! -f /var/www/.env ]; then \
      cp /var/www/.env.example /var/www/.env ; \
    fi \
 && chown www-data:www-data /var/www/.env \
 && chmod 640 /var/www/.env

# Permissions
RUN chown -R www-data:www-data /var/www \
 && chmod -R 755 /var/www

# Install deps PROD uniquement
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# Cache Laravel
RUN php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear

# Nettoyage
RUN apk del .build-deps

# ===============================
# 2. RUNTIME STAGE
# ===============================
FROM php:8.4-fpm-alpine

WORKDIR /var/www

# Force la mise à jour des paquets
RUN apk update && apk upgrade --no-cache

# Dépendances runtime
RUN apk add --no-cache \
    bash \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    oniguruma \
    postgresql-libs \
    postgresql-client \
    icu-libs \
    supervisor \
    libreoffice \
    fontconfig \
    ttf-dejavu
# Refresh font cache so LibreOffice embeds correct fonts in PDFs (fixes squares/garbled chars in viewer)
RUN fc-cache -fv

# Extensions compilées depuis le builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Code final
COPY --from=builder /var/www /var/www

# Configuration Supervisor (IMPORTANT: bon chemin)
RUN mkdir -p /etc/supervisor/conf.d /var/log/supervisor
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf

# Entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
COPY docker/paperless_init.sh /entrypoint-paperless_init.sh
RUN chmod +x /entrypoint.sh /entrypoint-paperless_init.sh

# Créer les dossiers nécessaires
RUN mkdir -p /var/www/storage/logs \
 && chown -R www-data:www-data /var/www/storage \
 && chmod -R 775 /var/www/storage

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]