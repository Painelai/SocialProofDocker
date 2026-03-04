FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite \
    && apt-get clean

RUN a2enmod rewrite
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

COPY . /var/www/html/

RUN mkdir -p /var/www/html/database \
    && chown -R www-data:www-data /var/www/html \
    && chmod 755 /var/www/html/database

EXPOSE 80
CMD ["apache2-foreground"]
