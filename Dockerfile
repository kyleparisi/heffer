FROM php:7.0-apache

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN ln -s -r /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
RUN a2enmod rewrite

RUN apt-get install libmagickwand-dev imagemagick pkg-config libmagickwand-dev -y
RUN yes | pecl install imagick
RUN echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini

COPY ./ /var/www/html/