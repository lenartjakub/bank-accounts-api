FROM php:8.1.1-fpm-alpine

ENV PROJECT_PATH /app

WORKDIR /app

RUN apk add --update libzip-dev oniguruma-dev nginx openssl-dev icu icu-libs icu-dev bash git curl netcat-openbsd g++ zip make \
    && docker-php-ext-install zip mbstring intl \
    && docker-php-ext-install pdo pdo_mysql

RUN rm -rf /var/cache/apk/*

RUN mkdir -p /tmp/nginx/client-body

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer

RUN mkdir -p $PROJECT_PATH

COPY . /app

RUN cp .env.example .env

RUN composer install

COPY ./entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]
