FROM php:8.2-apache

# Installer l'extension MySQL pour PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier les fichiers
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80