FROM php:8.2-cli
WORKDIR /usr/src/codingTest

# composer
COPY . .
RUN apt-get update
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip
RUN curl https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install
RUN composer dump-autoload

# Laravel
RUN cp .env.example .env
RUN php artisan key:generate

# xdebug for test coverage
RUN pecl install xdebug
RUN echo zend_extension=$(find / -name xdebug.so) >> /usr/local/etc/php/conf.d/docker-php-ext-sodium.ini
RUN echo xdebug.mode=coverage >> /usr/local/etc/php/conf.d/docker-php-ext-sodium.ini

CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
