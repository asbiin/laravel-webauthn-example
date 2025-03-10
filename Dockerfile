## Build assets
FROM node:22 AS node

WORKDIR /var/www/html
COPY . ./
RUN set -ex; \
    \
    yarn install --immutable; \
    yarn run build


## Image
FROM php:8.4-apache

# entrypoint.sh dependencies
RUN set -ex; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        bash \
        busybox-static \
        unzip \
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
        fileinfo \
        zip \
        bcmath \
        gmp \
        pdo_mysql \
        mysqli \
        pdo_pgsql \
    ; \
    \
# pecl will claim success even if one install fails, so we need to perform each install separately
    pecl install APCu; \
    pecl install redis; \
    \
    docker-php-ext-enable \
        apcu \
        redis \
    ; \
    \
# reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
    apt-mark auto '.*' > /dev/null; \
    apt-mark manual $savedAptMark; \
    ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
        | awk '/=>/ { so = $(NF-1); if (index(so, "/usr/local/") == 1) { next }; gsub("^/(usr/)?", "", so); print so }' \
        | sort -u \
        | xargs -r dpkg-query -S \
        | cut -d: -f1 \
        | sort -u \
        | xargs -rt apt-mark manual; \
    \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
    rm -rf /var/lib/apt/lists/*

# Set crontab for schedules
RUN set -ex; \
    \
    mkdir -p /var/spool/cron/crontabs; \
    rm -f /var/spool/cron/crontabs/root; \
    echo '* * * * * php /var/www/html/artisan schedule:run -v' > /var/spool/cron/crontabs/www-data

# Opcache
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="20000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"
# Limits
ENV PHP_MEMORY_LIMIT="512M" \
    PHP_UPLOAD_LIMIT="512M"
RUN set -ex; \
    \
    docker-php-ext-enable opcache; \
    { \
        echo '[opcache]'; \
        echo 'opcache.enable=1'; \
        echo 'opcache.revalidate_freq=0'; \
        echo 'opcache.validate_timestamps=${PHP_OPCACHE_VALIDATE_TIMESTAMPS}'; \
        echo 'opcache.max_accelerated_files=${PHP_OPCACHE_MAX_ACCELERATED_FILES}'; \
        echo 'opcache.memory_consumption=${PHP_OPCACHE_MEMORY_CONSUMPTION}'; \
        echo 'opcache.max_wasted_percentage=${PHP_OPCACHE_MAX_WASTED_PERCENTAGE}'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.fast_shutdown=1'; \
    } > $PHP_INI_DIR/conf.d/opcache-recommended.ini; \
    \
    echo 'apc.enable_cli=1' >> $PHP_INI_DIR/conf.d/docker-php-ext-apcu.ini; \
    \
    { \
        echo 'memory_limit=${PHP_MEMORY_LIMIT}'; \
        echo 'upload_max_filesize=${PHP_UPLOAD_LIMIT}'; \
        echo 'post_max_size=${PHP_UPLOAD_LIMIT}'; \
    } > $PHP_INI_DIR/conf.d/limits.ini;

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

# set apache config LimitRequestBody
ENV APACHE_BODY_LIMIT=1073741824
RUN set -ex; \
    \
    { \
        echo 'LimitRequestBody ${APACHE_BODY_LIMIT}'; \
    } > $APACHE_CONFDIR/conf-available/apache-limits.conf; \
    a2enconf apache-limits

RUN set -ex; \
    \
    APACHE_DOCUMENT_ROOT=/var/www/html/public; \
    sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" $APACHE_CONFDIR/sites-available/*.conf; \
    sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" $APACHE_CONFDIR/apache2.conf $APACHE_CONFDIR/conf-available/*.conf

WORKDIR /var/www/html


# Copy the local (outside Docker) source into the working directory,
# copy system files into their proper homes, and set file ownership
# correctly
COPY --chown=www-data:www-data . ./

ARG SENTRY_RELEASE
RUN set -ex; \
    \
    if [ -n "$SENTRY_RELEASE" ]; then echo -n "$SENTRY_RELEASE" > config/.release; fi; \
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

# Install node
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

# Install assets
COPY --from=node --chown=www-data:www-data /var/www/html/public/build ./public/build
COPY --from=node --chown=www-data:www-data /var/www/html/bootstrap/ssr ./bootstrap/ssr
COPY --from=node /var/www/html/node_modules ./node_modules

COPY --chown=www-data:www-data scripts/docker/.env.production .env
COPY scripts/docker/entrypoint.sh \
    scripts/docker/cron.sh \
    scripts/docker/queue.sh \
    scripts/docker/ssr.sh \
    /usr/local/bin/

ENTRYPOINT [ "entrypoint.sh" ]
CMD ["apache2-foreground"]
