FROM alpine:3 AS cloner
ARG COMMIT_HASH
RUN apk add --no-cache bash git
RUN git clone https://github.com/ApertureDevs/sea-of-bottles.git /app \
    && cd /app \
    && git checkout $COMMIT_HASH

FROM php:8.0-cli as builder
ARG APP_ENV
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN apt update \
    && apt install -y libpq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_pgsql
RUN apt update \
    && apt install -y libicu-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl
RUN apt update \
    && apt install -y librabbitmq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install amqp \
    && docker-php-ext-enable amqp
RUN docker-php-ext-install opcache
RUN apt update \
    && apt install -y zip \
    && rm -rf /var/lib/apt/lists/*
COPY --from=cloner /app/api /app
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN cd /app \
    && if [ "$APP_ENV" = "dev" ]; then composer install --prefer-dist --no-suggest; else composer install --prefer-dist --no-suggest --no-dev --optimize-autoloader --classmap-authoritative; fi \
    && bin/console cache:warmup

FROM php:8.0-fpm
RUN apt update \
    && apt install -y libpq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_pgsql
RUN apt update \
    && apt install -y libicu-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl
RUN apt update \
    && apt install -y librabbitmq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install amqp \
    && docker-php-ext-enable amqp
RUN docker-php-ext-install opcache
COPY --from=builder /app /app
WORKDIR /app
