FROM composer as vendor

WORKDIR /app

COPY . .

RUN composer update

FROM php:8.2-fpm-alpine

WORKDIR /app

EXPOSE 8000

COPY --from=vendor /app .

RUN set -ex \
  && apk --no-cache add \
    mysql-dev \
    gmp-dev

RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    gmp

RUN php artisan key:generate

CMD php artisan serve --host=0.0.0.0 --port=8000

