FROM php:7.4.30-apache
COPY ./api /var/www/html/
COPY ./database/connection.php /var/www/html/database/
RUN docker-php-ext-install pdo_mysql