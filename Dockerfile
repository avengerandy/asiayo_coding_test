FROM php:8.2-cli
WORKDIR /usr/src/codingTest
COPY . .

# xdebug for test coverage
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo xdebug.mode=coverage >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# zip for composer
RUN apt-get update
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip

# composer
RUN curl https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN groupadd -r laravel
RUN useradd -r -g laravel laravel
RUN chown -R laravel:laravel /usr/src/codingTest
USER laravel
RUN composer install
RUN composer dump-autoload

# Laravel
RUN cp .env.example .env
RUN php artisan key:generate

CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
