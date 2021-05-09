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
	make docker-compose && \
    make docker-npm && \
    make docker-migrate && \
	stat .env || cp .env.example .env && docker compose run --rm app php artisan key:generate

docker-npm:
	docker run --rm -v $(PWD):/app -w /app node:current-alpine3.13 npm install
	docker run --rm -v $(PWD):/app -w /app node:current-alpine3.13 npm run dev

docker-compose-install:
	docker run --rm -v $(PWD):/app -w /app -u $(shell id -u) composer:latest composer install

docker-test:
	docker-compose run --rm app make test
	make docker-down

docker-bash:
	docker compose run --rm app bash

docker-migrate:
	docker-compose run app php artisan migrate

docker-down:
	docker-compose down

docker-up:
	docker compose up -d --build
