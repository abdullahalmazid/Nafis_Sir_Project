FROM php:8.1-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy all files into the web root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
