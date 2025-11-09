dev:
	npm run watch

list:
	php artisan route:list --except-vendor

migrate:
	php artisan migrate

test:
	php artisan test

clear:
	php artisan queue:clear

flush:
	php artisan queue:flush

work:
	php artisan queue:work

prune:
	php artisan telescope:prune --hours=0
