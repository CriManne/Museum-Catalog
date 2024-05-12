FROM php:8.3-cli

USER root

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        unzip

RUN docker-php-ext-install pdo_mysql zip

RUN pecl uninstall xdebug && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

EXPOSE 80

WORKDIR /app

USER www-data



