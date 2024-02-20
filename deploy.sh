#!/bin/bash
php artisan down
git reset --hard
git pull
php artisan migrate
composer install --prefer-dist --optimize-autoloader --no-dev -o --no-interaction
php artisan optimize:clear
php artisan route:cache
php artisan view:cache
php artisan event:cache
npm install
npm run production
php artisan up
