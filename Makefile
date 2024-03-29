build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

restart: down up

init:
	composer install && bin/console d:m:migrate --no-interaction && bin/console d:fixtures:load --no-interaction

fixtures:
	bin/console d:fixtures:load --no-interaction

migrations:
	bin/console d:m:migrate --no-interaction

bash:
	docker exec -it bank_account_api bash
