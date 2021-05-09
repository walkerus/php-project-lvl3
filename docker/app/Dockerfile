FROM php:8.0-fpm

RUN apt-get update && apt-get upgrade -y \
    && apt-get install apt-utils -y \
    && apt-get install git zip vim libzip-dev libgmp-dev libffi-dev libssl-dev libpq-dev -y \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) sockets zip gmp pcntl bcmath ffi pgsql pdo_pgsql \
    && docker-php-source delete \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY opcache.ini /usr/local/etc/php/conf.d/
COPY php-fpm-custom.conf /etc/php-fpm.d/php-fpm-custom.conf

USER ${WEB_USER_ID}
