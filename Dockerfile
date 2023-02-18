## Build assets
FROM node:18 AS yarn

WORKDIR /var/www/html
COPY . ./
RUN set -ex; \
    \
    yarn install; \
    yarn run build


## Image
FROM php:8.2-apache

# entrypoint.sh dependencies
RUN set -ex; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        bash \
        busybox-static \
    ; \
    rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN set -ex; \
    \
    savedAptMark="$(apt-mark showmanual)"; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        libicu-dev \
        zlib1g-dev \
        libpq-dev \
        libzip-dev \
        libgmp-dev \
    ; \
    \
    docker-php-ext-configure intl; \
    docker-php-ext-configure gmp; \
    docker-php-ext-install -j$(nproc) \
        intl \
        zip \
        bcmath \
        gmp \
        pdo_mysql \
        mysqli \
        pdo_pgsql \
    ; \
    \
# reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
    apt-mark auto '.*' > /dev/null; \
    apt-mark manual $savedAptMark; \
        ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
        | awk '/=>/ { print $3 }' \
        | sort -u \
        | xargs -r dpkg-query -S \
        | cut -d: -f1 \
        | sort -u \
        | xargs -rt apt-mark manual; \
        \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
    rm -rf /var/lib/apt/lists/*

RUN set -ex; \
    \
    a2enmod headers rewrite remoteip; \
    { \
        echo RemoteIPHeader X-Real-IP; \
        echo RemoteIPTrustedProxy 10.0.0.0/8; \
        echo RemoteIPTrustedProxy 172.16.0.0/12; \
        echo RemoteIPTrustedProxy 192.168.0.0/16; \
    } > $APACHE_CONFDIR/conf-available/remoteip.conf; \
    a2enconf remoteip

RUN set -ex; \
    APACHE_DOCUMENT_ROOT=/var/www/html/public; \
    sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" $APACHE_CONFDIR/sites-available/*.conf; \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" $APACHE_CONFDIR/apache2.conf $APACHE_CONFDIR/conf-available/*.conf

WORKDIR /var/www/html


# Copy the local (outside Docker) source into the working directory,
# copy system files into their proper homes, and set file ownership
# correctly
COPY --chown=www-data:www-data . ./

RUN set -ex; \
    \
    mkdir -p bootstrap/cache; \
    mkdir -p storage; \
    chown -R www-data:www-data bootstrap/cache storage; \
    chmod -R g+w bootstrap/cache storage

# Composer installation
RUN curl -sS -o composer-setup.php https://getcomposer.org/installer && \
    php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer && \
    rm -f composer-setup.php

# Install composer dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -ex; \
    \
    mkdir -p storage/framework/views; \
    composer install --no-progress --no-interaction --prefer-dist --optimize-autoloader --no-dev; \
    composer clear-cache; \
    rm -rf .composer

# Install assets
COPY --from=yarn --chown=www-data:www-data /var/www/html/public/build ./public/build

COPY --chown=www-data:www-data scripts/docker/.env.production .env
COPY scripts/docker/entrypoint.sh \
    scripts/docker/cron.sh \
    scripts/docker/queue.sh \
    /

ENTRYPOINT [ "/entrypoint.sh" ]
CMD ["apache2-foreground"]
