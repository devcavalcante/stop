FROM php:8.1-fpm-alpine

ARG APP_ENV
ENV APP_ENV $APP_ENV
ARG XDEBUG_MODE
ENV XDEBUG_MODE $XDEBUG_MODE
ARG XDEBUG_IDEKEY
ENV XDEBUG_IDEKEY $XDEBUG_IDEKEY
ARG XDEBUG_HANDLER
ENV XDEBUG_HANDLER $XDEBUG_HANDLER
ARG XDEBUG_PORT
ENV XDEBUG_PORT $XDEBUG_PORT

RUN apk add --no-cache build-base
RUN apk --no-cache add autoconf

RUN apk add --update --no-cache \
    lz4-dev \
    lz4-libs \
    ssl_client \
    libpng \
    libjpeg-turbo \
    linux-headers \
    && rm -rf /var/cache/apk/*

RUN pecl channel-update pecl.php.net \
    && pecl install -o -f mongodb \
    && docker-php-ext-enable mongodb

RUN apk --update add --no-cache openssl bash nodejs npm
RUN docker-php-ext-install bcmath opcache

WORKDIR /app

RUN rm -rf /app
RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

RUN chown -R www-data:www-data /app && chmod -R 755 /app

RUN composer clearcache && composer install --no-interaction --optimize-autoloader
RUN php artisan key:generate && php artisan config:cache

EXPOSE 9000
ENTRYPOINT [ "php-fpm" ]

