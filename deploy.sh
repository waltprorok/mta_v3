#!/bin/bash
php artisan down
git reset --hard
git pull
php artisan migrate
composer install --prefer-dist --no-dev -o
composer dump-autoload
php artisan optimize:clear
php artisan route:cache
php artisan view:cache
php artisan route:cache
npm run production
php artisan up