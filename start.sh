#!/usr/bin/env bash
set -e

cd /var/www/html

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

php artisan storage:link || true
php artisan config:cache
php artisan route:cache || true
php artisan view:cache
php artisan migrate --force
