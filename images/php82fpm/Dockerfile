FROM php:8.2-fpm

RUN apt-get update
RUN apt-get install -y curl git zip

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

USER "1000:1000"

COPY php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www

ENTRYPOINT ["php-fpm"]