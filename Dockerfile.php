FROM php:8.3-fpm-alpine

ARG user

ARG uid

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    postgresql-dev \
    unzip \
    autoconf \
    make \
    g++

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql pgsql mysqli mbstring exif pcntl bcmath gd zip intl

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PATH="$PATH:/usr/local/bin"

COPY . .

RUN addgroup -g $uid $user \
    && adduser -u $uid -G $user -s /bin/sh -D $user

RUN chown -R $user:$user /var/www \
    && chown -R $user:$user /home/$user

USER $user

COPY composer.* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

EXPOSE 9000
EXPOSE 5173

CMD ["php-fpm"]