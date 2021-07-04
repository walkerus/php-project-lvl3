install:
	composer install
	npm install && npm run dev
	stat .env || cp .env.example .env && php artisan key:generate

lint:
	composer run-script phpcs -- --standard=PSR12 app tests
	composer run-script phpstan analyse app tests

test:
	php artisan test

docker-lint:
	docker run --rm -v $(PWD):/app -w /app composer:latest make lint

docker-install:
	stat .env || cp .env.example .env && \
	make docker-compose-install && \
	docker run --rm -v $(PWD):/app -w /app php:8.0-fpm php artisan key:generate && \
    make docker-npm && \
    make docker-migrate

docker-install-ci:
	stat .env || cp .env.example .env && \
	make docker-compose-install && \
	docker run --rm -v $(PWD):/app -w /app php:8.0-fpm php artisan key:generate && \
    make docker-migrate

docker-npm:
	docker run --rm -v $(PWD):/app -w /app node:current-alpine3.13 npm install
	docker run --rm -v $(PWD):/app -w /app node:current-alpine3.13 npm run dev

docker-compose-install:
	docker run --rm -v $(PWD):/app -w /app -u $(shell id -u) composer:latest composer install

docker-test:
	docker-compose run --rm app make test

docker-test-ci:
	docker-compose -f docker-compose-test.yml -p page-analizer-test run app ./artisan migrate
	docker-compose -f docker-compose-test.yml -p page-analizer-test run --rm app make test

docker-bash:
	docker compose run --rm app bash

docker-migrate:
	docker-compose run --rm -u $(shell id -u):$(shell id -g) app php artisan migrate

docker-down:
	docker-compose down

docker-up:
	docker compose up -d
