#!/bin/sh
set -eu

exec php /var/www/html/artisan inertia:start-ssr >/proc/1/fd/1 2>/proc/1/fd/2
