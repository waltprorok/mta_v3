list:
	php artisan route:list --except-vendor

clear:
	php artisan queue:clear

coverage:
	php artisan test --without-tty --coverage-html tests/Reports/Coverage

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

npm-dev:
	npm run watch

npm-prod:
	npm run production
