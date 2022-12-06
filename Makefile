.PHONY: all
all: help

.PHONY: tinker
tinker: ## Run the tinker command.
	@echo "+ $@"
	@docker-compose exec worker1 \
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
	@docker run -it -w /data -v ${PWD}:/data:delegated -e COMPOSER_AUTH='{"http-basic":{"repo.packagist.com":{"username":"token","password":"3117616040c636044c026223aed6d3c7e9cd097594061b487559fe5b2ba7"}}}' --entrypoint composer --rm registry.gitlab.com/grahamcampbell/php:8.1-base install --no-scripts
	@docker-compose exec worker1 \
		composer install
	@docker-compose exec worker1 \
		php artisan migrate:fresh
	@docker-compose run --rm npm \
		npm ci
	@docker-compose run --rm npm \
		npm run dev
	@rm -f .env.testing
	@cp .env.testing.example .env.testing


.PHONY: fe-test
fe-test: ## Run the project Jest test suite.
	@echo "+ $@"
	@docker-compose run --rm npm \
		npm run test
