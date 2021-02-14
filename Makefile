install:
	composer install
	stat .env || cp .env.example .envinstall:

lint:
	composer run-script phpcs -- --standard=PSR12 app tests
	composer run-script phpstan analyse app tests

docker-lint:
	docker run --rm -v $(PWD):/app -w /app composer:latest make lint

docker-install:
	docker run --rm -v $(PWD):/app -w /app -u $(id -u) composer:latest composer install --ignore-platform-reqs
	stat .env || cp .env.example .envinstall:
