FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql zip intl gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV TZ=${TIMEZONE}

COPY --chown=${RUN_USER}:${RUN_GROUP} . ${DOCUMENT_ROOT}

USER ${RUN_USER}:${RUN_GROUP}
WORKDIR ${DOCUMENT_ROOT}

RUN chown -R www-data:www-data /var/www

EXPOSE 9000

CMD ["php-fpm"]
