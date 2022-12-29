.PHONY: all
all: help

.PHONY: tinker
tinker: ## Run the tinker command.
	@echo "+ $@"
	@docker-compose exec php \
		php artisan tinker

.PHONY: up
up: ## Run the all container services specified in docker-compose.
	@echo "+ $@"
	@docker-compose up --build

.PHONY: install
install: ## Install the project dependencies, bootstrap the database and compile the frontend assets.
	@echo "+ $@"
	@rm -f .env
	@cp .env.example .env
	@docker-compose exec php \
		composer install
	@docker-compose exec php \
		php artisan key:generate
	@docker-compose exec php \
		php artisan migrate:fresh
	@docker-compose run --rm npm \
		npm ci
	@docker-compose run --rm npm \
		npm run dev
	@rm -f .env.testing
	@cp .env.testing.example .env.testing
	@docker-compose exec db \
		mysql --user="root" --password="secret" --execute "create database if not exists csv_testing;"


.PHONY: fe-test
fe-test: ## Run the project Jest test suite.
	@echo "+ $@"
	@docker-compose run --rm npm \
		npm run test
