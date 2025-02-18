FROM php:8.4.2-apache
COPY ./index.php /var/www/html/index.php
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli
