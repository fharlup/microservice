FROM php:8.2-apache

# Install extensions

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

COPY src/ /var/www/html/
COPY init.sql /docker-entrypoint-initdb.d/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

    
EXPOSE 80
