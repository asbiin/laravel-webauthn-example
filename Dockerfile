FROM php:7.3-apache

RUN apt-get update && \
    apt-get -y --no-install-recommends install \
      git unzip && \
    rm -rf /var/lib/apt/lists/*

# Install common extensions
RUN apt-get update && \
    apt-get -y autoremove && \
    apt-get -y --no-install-recommends install \
                libgmp-dev && \
    docker-php-ext-install -j$(nproc) \
                gmp && \
    apt-get -y clean && \
    rm -rf /var/lib/apt/lists/*

COPY . .
RUN mkdir -p bootstrap/cache; \
    mkdir -p storage; \
    chgrp -R www-data bootstrap/cache storage; \
    chmod -R g+w bootstrap/cache storage
COPY .env.example .env

# Composer installation
RUN curl -sS -o composer-setup.php https://getcomposer.org/installer && \
    php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer && \
    rm -f composer-setup.php

# Install composer dependencies
RUN composer global require hirak/prestissimo && \
    composer install --no-interaction --no-suggest --no-progress --no-dev && \
    composer global remove hirak/prestissimo && \
    composer clear-cache && \
    rm -rf .composer

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite cache headers

RUN set -ex && \
	cd / && \
	{ \
		echo '#!/bin/sh'; \
        echo 'set -ex'; \
        echo 'if expr "$1" : "apache" 1>/dev/null; then'; \
        echo '  touch database/database.sqlite'; \
        echo '  chgrp www-data database database/database.sqlite'; \
        echo '  chmod g+w database database/database.sqlite'; \
		echo '  php /var/www/html/artisan migrate --force -v'; \
		echo 'fi'; \
		echo 'exec "$@"'; \
	} | tee entrypoint.sh && \
	chmod a+x entrypoint.sh
ENTRYPOINT [ "/entrypoint.sh" ]

EXPOSE 80
CMD ["apache2-foreground"]
