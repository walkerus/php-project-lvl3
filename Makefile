install:
	composer install
	stat .env || cp .env.example .env && php artisan key:generate

lint:
	composer run-script phpcs -- --standard=PSR12 app tests
	composer run-script phpstan analyse app tests

test:
	php artisan test

docker-lint:
	docker run --rm -v $(PWD):/app -w /app composer:latest make lint

docker-install:
	docker run --rm -v $(PWD):/app -w /app -u $(id -u) composer:latest composer install
	stat .env || cp .env.example .env && docker-compose run --rm app php artisan key:generate

docker-test:
	docker-compose run --rm app make test

