FROM php:8.2-apache

# Extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Desabilitar MPM duplicado e habilitar apenas event
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true \
    && a2enmod mpm_prefork rewrite headers

# Suprimir warning ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Permitir .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copiar projeto
COPY . /var/www/html/

# Permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
