## Сборка

`git clone git@github.com:nazarevrn/goods.git .`

### Запуск через докер (тестировал только на Linux-хосте)

`make init`

по дефолту приложение будет доступно <a href="http://localhost:5000">localhost:5000</a>

### Обёртки над докером
####Важно!
PHP_SERVICE_NAME в Makefile должен соответствовать названию контейнера  PHP & Apache из docker-compose.yml

запуск 

`make up`

остановка 

`make down`

консоль внутрь веб-контейнера

`make shell`

миграции

`make migrate-up` и `make migrate-down`

composer
`make composer-install` и `make composer update` 

## Проверка
В папке postman_collection лежит импортированная коллекция запросов из одноименного ПО
