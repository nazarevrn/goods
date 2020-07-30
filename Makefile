PHP_SERVICE_NAME = php-goods
COMPOSER = @docker-compose exec \
           		--user www-data \
                   ${PHP_SERVICE_NAME} \
                   composer
MIGRATE = @docker-compose exec \
          		--user www-data \
                  ${PHP_SERVICE_NAME} \
                  ./php artisan

-include .env

init: docker-down-clear docker-pull docker-build docker-up composer-install

init-configs:
	@cp .env.default .env

up: docker-up
down: docker-down
restart: docker-down docker-up

docker-up:
	@docker-compose up -d

docker-down:
	@docker-compose down --remove-orphans

docker-down-clear:
	@docker-compose down -v --remove-orphans

docker-pull:
	@docker-compose pull --ignore-pull-failures

docker-build:
	@docker-compose build

migrate-up:
	$(MIGRATE) migrate

migrate-down:
	$(MIGRATE):rollback

composer-install:
	$(COMPOSER) install

composer-update:
	$(COMPOSER) update

shell:
	@docker-compose exec --user www-data ${PHP_SERVICE_NAME} /bin/bash

