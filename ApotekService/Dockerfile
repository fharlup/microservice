FROM php:8.2-apache

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY src/ /var/www/html/
COPY init.sql /docker-entrypoint-initdb.d/

EXPOSE 80
