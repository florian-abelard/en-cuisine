#------------------------------------------------------------------------------
# Main Makefile
#------------------------------------------------------------------------------

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)
ROOT_PATH=$(shell pwd)
DOCKER_COMPOSE_FILE?=./docker-compose.yml
DOCKER_COMPOSE_ANALYSER_FILE?=./docker/docker-compose-analyser.yml
DOCKER_COMPOSE_BUILDER_FILE?=./docker/docker-compose-builder.yml

export USER_ID
export GROUP_ID
export ROOT_PATH

#------------------------------------------------------------------------------

include .env
export $(shell sed 's/=.*//' .env)

ifneq (,$(wildcard application/backend/.env))
	include application/backend/.env
	export $(shell sed 's/=.*//' application/backend/.env)
endif

ifneq (,$(wildcard application/backend/.env.local))
	include application/backend/.env.local
	export $(shell sed 's/=.*//' application/backend/.env.local)
endif

#------------------------------------------------------------------------------

include makefiles/*.mk

#------------------------------------------------------------------------------

init: composer-install ## install project dependencies

up: up-app # db-wait-for db-init ## up application

up-app: .env ##@docker build and start containers
	docker-compose -f ${DOCKER_COMPOSE_FILE} up -d

down: ##@docker stop and remove containers and volumes
	docker-compose -f ${DOCKER_COMPOSE_FILE} down --volumes

logs: ##@docker displays containers log
	docker-compose logs -f -t --tail="5"

#------------------------------------------------------------------------------

bash-web: ## open a bash session in the web container
	docker-compose -f ${DOCKER_COMPOSE_FILE} exec web /bin/sh

bash-php: ## open a bash session in the php-fpm container
	docker-compose -f ${DOCKER_COMPOSE_FILE} exec --user ${USER_ID}:${GROUP_ID} php /bin/sh

bash-node: ## open a bash session in the node container
	docker-compose -f ${DOCKER_COMPOSE_BUILDER_FILE} run --user ${USER_ID}:${GROUP_ID} node /bin/sh

#------------------------------------------------------------------------------

build: ##@docker build containers
	docker-compose -f ${DOCKER_COMPOSE_FILE} build

rebuild: build up ##@docker rebuild and start containers

clean: down ##@docker clean docker containers
	docker container ls -a | grep "${APP_NAME}" | awk '{print $1}' | xargs --no-run-if-empty docker container rm
	docker image rm $(docker images -a -q)

#------------------------------------------------------------------------------

.DEFAULT_GOAL := help

help:
	@echo "================================================================================"
	@echo " Main Makefile"
	@echo "================================================================================"
	@echo
	@perl -e '$(HELP_FUNC)' $(MAKEFILE_LIST)
	@echo "================================================================================"

#------------------------------------------------------------------------------

.PHONY: init up up-app build down rebuild clean help
