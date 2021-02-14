install:
	composer install
	stat .env || cp .env.example .env

lint:
	composer run-script phpcs -- --standard=PSR12 app tests
	composer run-script phpstan analyse app tests

test:
	php artisan test

docker-lint:
	docker run --rm -v $(PWD):/app -w /app composer:latest make lint

docker-install:
	docker run --rm -v $(PWD):/app -w /app -u $(id -u) composer:latest composer install
	stat .env || cp .env.example .env:

docker-test:
	docker-compose run --rm -w /app app make test

