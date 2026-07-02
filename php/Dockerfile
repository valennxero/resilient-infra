FROM php:8.2-fpm-alpine
 
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN apk add --no-cache libpng-dev libjpeg-turbo-dev freetype-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd
RUN apk add --no-cache libzip-dev && docker-php-ext-install zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY src/ .
RUN composer install --no-dev --optimize-autoloader
