FROM php:8.2-fpm-alpine

# Install build dependencies
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    curl-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip \
    && apk del .build-deps \
    && apk add --no-cache \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    curl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy source code
COPY src/ .

# Install dependency PHP
RUN composer install --no-dev --optimize-autoloader
