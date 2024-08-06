FROM php:8.2-cli
WORKDIR /usr/src/codingTest

CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
