FROM php:8.3-fpm AS base

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libgd-dev
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath
RUN docker-php-ext-configure gd --with-external-gd
RUN docker-php-ext-install gd

# Install PECL extensions
RUN pecl install redis

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Add unprivileged user
ARG user=www
ARG uid=1000

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    chown -R $user:$user /var/www/html

# Change current user to $user
USER $user

FROM base as dev

USER root

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY ./docker/php/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini

USER $user
