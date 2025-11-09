dev:
	npm run watch

list:
	php artisan route:list --except-vendor

clear:
	php artisan queue:clear

flush:
	php artisan queue:flush

work:
	php artisan queue:work

log:
	php artisan log:clear

migrate:
	php artisan migrate

test:
	php artisan test

prune:
	php artisan telescope:prune --hours=0
