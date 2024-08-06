FROM php:8.2-cli
WORKDIR /usr/src/codingTest

RUN pecl install xdebug
RUN echo zend_extension=$(find / -name xdebug.so) >> /usr/local/etc/php/conf.d/docker-php-ext-sodium.ini
RUN echo xdebug.mode=coverage >> /usr/local/etc/php/conf.d/docker-php-ext-sodium.ini

CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]