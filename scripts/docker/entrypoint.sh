#!/bin/bash

set -Eeo pipefail

if expr "$1" : "apache" 1>/dev/null || [ "$1" = "php-fpm" ]; then

    MONICADIR=/var/www/html
    ARTISAN="php ${MONICADIR}/artisan"

    # Ensure storage directories are present
    STORAGE=${MONICADIR}/storage
    mkdir -p ${STORAGE}/logs
    mkdir -p ${STORAGE}/app/public
    mkdir -p ${STORAGE}/framework/views
    mkdir -p ${STORAGE}/framework/cache
    mkdir -p ${STORAGE}/framework/sessions
    chown -R www-data:www-data ${STORAGE}
    chmod -R g+rw ${STORAGE}

    if [ "${DB_CONNECTION:-sqlite}" == "sqlite" ]; then
        dbPath="${DB_DATABASE:-database/database.sqlite}"
        if [ ! -f "$dbPath" ]; then
            echo "Creating sqlite database at ${dbPath} — make sure it will be saved in a persistent volume."
            touch "$dbPath"
            chown www-data:www-data "$dbPath"
        fi
    fi

    if [ -z "${APP_KEY:-}" ]; then
        ${ARTISAN} key:generate --no-interaction
        key=$(grep APP_KEY .env | cut -c 9-)
        echo "APP_KEY generated: $key — save it for later usage."
    else
        echo "APP_KEY already set."
    fi

    # Run migrations
    ${ARTISAN} setup --force -vv

fi

exec "$@"
