#!/bin/sh
set -eu

trap "php /var/www/html/artisan inertia:stop-ssr" SIGINT
exec php /var/www/html/artisan inertia:start-ssr >/proc/1/fd/1 2>/proc/1/fd/2
