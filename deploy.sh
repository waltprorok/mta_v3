#!/bin/bash
php artisan down
git reset --hard
git pull
php artisan migrate
composer install --prefer-dist --optimize-autoloader --no-dev -o --no-interaction
php artisan optimize:clear
php artisan optimize
npm install
npm run production
php artisan queue:restart
php artisan up
