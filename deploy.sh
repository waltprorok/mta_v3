#!/bin/bash
#php artisan down
git fetch origin
git reset --hard origin/master
php artisan migrate --force
export COMPOSER_ALLOW_SUPERUSER=1
composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction
php artisan optimize:clear
php artisan event:clear
npm ci --loglevel=error --no-audit --no-fund
npm run production
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan config:cache
php artisan queue:restart
#php artisan up
sudo supervisorctl status
