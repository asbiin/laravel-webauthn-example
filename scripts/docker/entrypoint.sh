#!/bin/bash

set -Eeo pipefail

if expr "$1" : "apache" 1>/dev/null || [ "$1" = "php-fpm" ]; then

    ROOT=/var/www/html
    ARTISAN="php ${ROOT}/artisan"

    # Ensure storage directories are present
    STORAGE=${ROOT}/storage
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
    ${ARTISAN} waitfordb
    ${ARTISAN} setup --force -vv
    ${ARTISAN} inertia:stop-ssr

fi

exec "$@"
