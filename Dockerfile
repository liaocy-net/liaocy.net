FROM php:8.2-fpm

USER root

WORKDIR /var/www/html

# パッケージのインストール
RUN apt-get update \
    && apt-get -y install git zip unzip vim zlib1g-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev libonig-dev nginx

RUN docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install exif \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copy php.ini
COPY ./conf/php.ini /usr/local/etc/php/php.ini

# copy nginx.conf
COPY conf/nginx.conf /etc/nginx/sites-enabled/default

# copy source
COPY . /var/www/html

# set permission
RUN chmod -R 777 /var/www/html/storage

# Install composer packages
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Runable entrypoint.sh
RUN chmod +x /var/www/html/conf/entrypoint.sh
