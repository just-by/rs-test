#!/usr/bin/make
# Makefile readme: <https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents>

SHELL = /bin/bash
DC_RUN_ARGS = --rm --user "$(shell id -u):$(shell id -g)"

.PHONY : help install build fixtures init test shell up down restart
.DEFAULT_GOAL : help

help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z0-9_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install composer dependencies
	docker-compose run $(DC_RUN_ARGS) --no-deps rs_task composer install --ansi --prefer-dist

build: ## Build app image
	docker-compose build rs_task

fixtures: ## Load fixtures for dev environment
	docker-compose run $(DC_RUN_ARGS) --no-deps rs_task php ./bin/console doctrine:fixtures:load -e dev

init: build install ## Build && install dependencies

test: ## Run tests
	docker-compose run $(DC_RUN_ARGS) rs_task composer test

shell: ## Start shell into php container
	docker-compose run $(DC_RUN_ARGS) rs_task sh

up: ## Create and start containers
	APP_UID=$(shell id -u) APP_GID=$(shell id -g) docker-compose up --detach --remove-orphans rs_task
	@printf "\n   \e[30;42m %s \033[0m\n\n" 'Navigate your browser to http://127.0.0.1:8001'

down: ## Stop all containers
	docker-compose down

restart: down up ## Restart containers

