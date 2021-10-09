FROM ubuntu:20.04 AS symfony-installer
RUN apt update \
    && apt install -y wget \
    && wget https://get.symfony.com/cli/installer -O - | bash

FROM ubuntu:20.04 AS dockerize-installer
ENV DOCKERIZE_VERSION v0.6.1
RUN apt update \
    && apt install -y wget \
    && wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /root -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

FROM php:8 AS composer-installer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

FROM php:8
ARG XDEBUG_VERSION=3.1.0
ENV XDEBUG_MODE='off'
RUN apt update \
    && apt install -y git \
    && rm -rf /var/lib/apt/lists/*
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
    && pecl install amqp-1.11.0beta \
    && docker-php-ext-enable amqp
RUN apt update \
    && apt install -y unzip zlib1g-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip
RUN pecl install xdebug-${XDEBUG_VERSION} \
    && docker-php-ext-enable xdebug
RUN docker-php-ext-install opcache
COPY php.ini /usr/local/etc/php/
COPY --from=symfony-installer /root/.symfony/bin/symfony /usr/local/bin/symfony
COPY --from=composer-installer /composer.phar /usr/local/bin/composer
COPY --from=dockerize-installer /root/dockerize /usr/local/bin/dockerize
