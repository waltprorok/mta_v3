#!/bin/bash
set -e

#php artisan down

# Check if package-lock.json changed
OLD_LOCK=$(git rev-parse HEAD:package-lock.json 2>/dev/null || echo "")

git fetch origin
git reset --hard origin/master

NEW_LOCK=$(git rev-parse HEAD:package-lock.json 2>/dev/null || echo "")

php artisan migrate --force

export COMPOSER_ALLOW_SUPERUSER=1
composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

php artisan optimize:clear
php artisan event:clear

# Only install npm dependencies when package-lock.json changes
if [ "$OLD_LOCK" != "$NEW_LOCK" ] || [ ! -d "node_modules" ]; then
    echo "Installing npm dependencies..."
    npm ci --loglevel=error --no-audit --no-fund
else
    echo "package-lock.json unchanged. Skipping npm ci."
fi

npm run production

php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan config:cache
php artisan queue:restart

#php artisan up

sudo supervisorctl status
