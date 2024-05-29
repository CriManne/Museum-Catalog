FROM php:8.3-apache

USER root

ENV APACHE_DOCUMENT_ROOT=/var/www/public
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY ./apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        unzip \
        nano \
        libapache2-mod-security2 \
        && a2enmod rewrite


RUN docker-php-ext-install pdo_mysql zip

RUN pecl uninstall xdebug && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');" \
    composer install \
    composer update

USER www-data



