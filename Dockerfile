
FROM php:8.3-apache

# Install PHP database drivers
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy website files into Apache's web directory
COPY . /var/www/html/

# Enable Apache rewrite support
RUN a2enmod rewrite

# Apache listens on port 80 inside the container
EXPOSE 80
