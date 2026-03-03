FROM php:8.2-apache

# Extensões necessárias
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configurar Apache para aceitar .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copiar projeto
COPY . /var/www/html/

# Permissões da pasta database (SQLite precisa escrever)
RUN mkdir -p /var/www/html/database \
    && chown -R www-data:www-data /var/www/html/database \
    && chmod 755 /var/www/html/database

# Porta do Render
ENV PORT=80
EXPOSE 80

CMD ["apache2-foreground"]
