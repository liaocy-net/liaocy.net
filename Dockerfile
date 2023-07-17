FROM php:8.2-fpm

USER root

WORKDIR /var/www/html

# パッケージのインストール
RUN apt-get update \
    && apt-get -y install git zip unzip vim zlib1g-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev

RUN docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install exif \
    && docker-php-ext-install pdo

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copy php.ini
COPY ./php.ini /usr/local/etc/php/php.ini

COPY . /var/www/html

# Install composer packages
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

