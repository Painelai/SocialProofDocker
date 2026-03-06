FROM php:8.2-apache

# Extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite e headers
RUN a2enmod rewrite headers

# Configurar ServerName para suprimir warning AH00534
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Permitir .htaccess em todo o projeto
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copiar projeto
COPY . /var/www/html/

# Permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
