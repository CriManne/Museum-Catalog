FROM php:8.2-cli

USER root

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        unzip

RUN docker-php-ext-install pdo_mysql zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

EXPOSE 80

WORKDIR /app

USER www-data



