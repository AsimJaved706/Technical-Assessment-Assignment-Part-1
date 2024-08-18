# Use an official PHP image with Apache
FROM php:8.1-apache

# Install necessary extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the PHP source code to the Apache web directory
COPY . /var/www/html/

# Set the working directory inside the container
WORKDIR /var/www/html/

# Give permissions to the Apache user
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Expose port 80 to the outside world
EXPOSE 80
