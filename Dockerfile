FROM php:8.0-apache

# Install required PHP extensions for MySQL connectivity
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite to handle the MVC project's routing system (.htaccess)
RUN a2enmod rewrite