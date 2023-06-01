FROM php:8.1-cli

WORKDIR /var/www/html

COPY . /var/www/html

RUN apt-get update && apt-get install -y supervisor

RUN apt-get install -y libzip-dev zip && \
    docker-php-ext-install opcache pdo pdo_mysql zip pcntl

RUN pecl install redis && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --optimize-autoloader --no-dev --ignore-platform-req=ext-imap

CMD ["php", "/var/www/html/artisan", "queue:work", "--sleep=3", "--tries=3"]
