SHELL := /bin/bash

up:
	docker compose up -d --build

down:
	docker compose down

reset:
	bash ./reset.sh

logs:
	docker compose logs -f --tail=200

shell:
	docker compose exec web bash

migrate:
	docker compose exec web php spark migrate --all

seed:
	docker compose exec web php spark db:seed DatabaseSeeder
