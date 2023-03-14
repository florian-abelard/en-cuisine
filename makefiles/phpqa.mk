#------------------------------------------------------------------------------
# PHPStan Makefile
#------------------------------------------------------------------------------

ECS_DOCKER_CMD = docker-compose -f ${DOCKER_COMPOSE_ANALYSER_FILE} run --rm -T phpqa ecs ${1}

# Cli arguments
ifneq (,$(filter ecs-check%, $(firstword $(MAKECMDGOALS))))
    COMPOSER_CLI_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    $(eval $(COMPOSER_CLI_ARGS):;@:)
endif

#------------------------------------------------------------------------------

ecs-check: ##@phpqa run ecs check on the specified folder
	$(call ECS_DOCKER_CMD, check --config /app/docker/images/phpqa/ecs.php $(COMPOSER_CLI_ARGS))

phpqa-rebuild: ##@phpqa rebuild the phpqa image
	docker-compose -f ${DOCKER_COMPOSE_ANALYSER_FILE} build phpqa

phpqa-bash:
	docker-compose -f ${DOCKER_COMPOSE_ANALYSER_FILE} run --rm -T phpqa sh

#------------------------------------------------------------------------------

.PHONY: phpstan-analyse
