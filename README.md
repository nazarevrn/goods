## Требования

docker >= 19.03

docker-compose >= 1.24.1

## Сборка

`git clone git@github.com:nazarevrn/goods.git .`

### Запуск через докер (тестировал только на Linux-хосте)

`make init`

применить миграции

`make migrate-up`

по дефолту приложение будет доступно <a href="http://localhost:5000">localhost:5000</a>

### Обёртки над докером
####Важно!
PHP_SERVICE_NAME в Makefile должен соответствовать названию контейнера  PHP & Apache из docker-compose.yml

####запуск 

`make up`

####остановка 

`make down`

####консоль внутрь веб-контейнера

`make shell`

####миграции

`make migrate-up` и `make migrate-down`

####composer

`make composer-install` и `make composer update` 

ну или внутри контейнера через

`make shell`

## Проверка
В папке postman_collection лежит импортированная коллекция запросов из одноименного ПО

##P.S

успел сделать далеко не всё что и как хотел.
