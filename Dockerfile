FROM php:8.4.16-fpm-alpine3.23 as BASE

WORKDIR /var/www/html

# Copy composer files
COPY ./composer.* ./
COPY ./local-packages ./local-packages

# Install system dependencies

RUN curl -s https://getcomposer.org/installer | php

# Install compose packages
RUN php composer.phar dump-autoload
RUN php composer.phar install

# Run application

FROM php:8.4.16-apache 

WORKDIR /var/www/html

COPY . .

COPY --from=BASE /var/www/html/vendor ./vendor

# Copy custom Apache site config into image and enable required modules
COPY apache-config.conf /etc/apache2/sites-available/thephprame.conf
RUN a2enmod rewrite proxy_fcgi setenvif && \
	a2ensite thephprame.conf && \
	a2dissite 000-default.conf || true



