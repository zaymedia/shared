init: docker-down-clear \
	app-clear \
	docker-pull docker-build docker-up \
	app-init

up: docker-up
down: docker-down
restart: down up
check: lint analyze #app-test

# Docker
docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

app-clear:
	docker run --rm -v ${PWD}/:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'

# Composer
app-init: app-permissions app-composer-install

app-permissions:
	docker run --rm -v ${PWD}/:/app -w /app alpine chmod 777 var/cache var/log var/test

app-composer-install:
	docker-compose run --rm php-cli composer install

app-composer-update:
	docker-compose run --rm php-cli composer update

# Lint and analyze
lint:
	docker-compose run --rm php-cli composer lint
	docker-compose run --rm php-cli composer php-cs-fixer fix -- --dry-run --diff

cs-fix:
	docker-compose run --rm php-cli composer php-cs-fixer fix

analyze:
	docker-compose run --rm php-cli composer psalm

# Tests
app-test:
	docker-compose run --rm php-cli composer test

app-test-coverage:
	docker-compose run --rm php-cli composer test-coverage

app-test-unit:
	docker-compose run --rm php-cli composer test -- --testsuite=unit

app-test-unit-coverage:
	docker-compose run --rm php-cli composer test-coverage -- --testsuite=unit

app-test-functional:
	docker-compose run --rm php-cli composer test -- --testsuite=functional

app-test-functional-coverage:
	docker-compose run --rm php-cli composer test-coverage -- --testsuite=functional

# git tag 1.0.50
# git push --tags
