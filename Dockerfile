FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip zip curl nano \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev \
    libpq-dev libicu-dev libxslt-dev \
    libcurl4-openssl-dev \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_mysql pdo_pgsql \
        mbstring zip exif pcntl bcmath \
        intl gd xml xsl opcache

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

# 🚨 IMPORTANT FIX HERE
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

RUN npm install
RUN npm run production

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD php artisan key:generate --force || true \
 && php artisan config:clear \
 && php artisan config:cache \
 && php artisan route:cache || true \
 && apache2-foreground
