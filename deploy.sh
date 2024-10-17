#!/bin/bash
php artisan down
git reset --hard
git pull
php artisan migrate --no-interaction
export COMPOSER_ALLOW_SUPERUSER=1
composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction
php artisan optimize:clear
php artisan event:clear
npm install
npm run production
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan config:cache
php artisan queue:restart
php artisan up
