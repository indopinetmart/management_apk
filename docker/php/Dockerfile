# ============================================================
# Laravel 11 + PHP-FPM 8.2 + Supervisor (Windows compatible)
# ============================================================

# Stage 1: Composer dependencies
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Stage 2: PHP Application
FROM php:8.2-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git supervisor \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev \
    libsodium-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mysqli mbstring gd zip sodium \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy vendor dari stage 1
COPY --from=vendor /app/vendor ./vendor

# Copy seluruh Laravel app
COPY . .

# Copy supervisord config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Buat log folder supaya Supervisor bisa menulis log
RUN mkdir -p /var/log/supervisor \
    && mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache /var/log/supervisor \
    && chmod -R 775 storage bootstrap/cache /var/log/supervisor

# Jalankan ulang autoload + package discover
RUN composer dump-autoload --optimize \
 && php artisan package:discover --ansi || true

# Konfigurasi PHP-FPM
RUN sed -i 's/^;*user = .*/user = www-data/' /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's/^;*group = .*/group = www-data/' /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's|^;*listen = .*|listen = 0.0.0.0:9000|' /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's|^;*catch_workers_output = .*|catch_workers_output = yes|' /usr/local/etc/php-fpm.d/www.conf \
 && echo "php_admin_value[error_log] = /proc/self/fd/2" >> /usr/local/etc/php-fpm.d/www.conf \
 && echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Expose PHP-FPM
EXPOSE 9000

# Default command untuk worker
CMD ["php-fpm", "-F"]
