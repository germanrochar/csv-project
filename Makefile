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
