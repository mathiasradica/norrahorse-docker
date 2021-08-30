FROM php:7.4-apache
COPY ./ /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN apt-get update && apt-get -y install git && \
    docker-php-ext-install pdo_mysql
CMD composer install && apache2ctl -D FOREGROUND