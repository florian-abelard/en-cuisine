#------------------------------------------------------------------------------
# PHPStan Makefile
#------------------------------------------------------------------------------

PHPQA_DOCKER_CMD = docker compose -f ${DOCKER_COMPOSE_ANALYSER_FILE} run --rm -T phpqa ${1}

# Cli arguments
ifneq (,$(filter ecs-check%, $(firstword $(MAKECMDGOALS))))
    COMPOSER_CLI_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    $(eval $(COMPOSER_CLI_ARGS):;@:)
endif

#------------------------------------------------------------------------------

ecs-check: ##@phpqa run ecs check on the specified folder
	$(call PHPQA_DOCKER_CMD, ecs check --config /app/docker/images/phpqa/ecs.php $(COMPOSER_CLI_ARGS))

phpmd: ##@phpqa run phpmd on the specified folder
	$(call PHPQA_DOCKER_CMD, phpmd /app/application/backend/src/ text /app/docker/images/phpqa/phpmd.xml)

phpqa-rebuild: ##@phpqa rebuild the phpqa image
	docker compose -f ${DOCKER_COMPOSE_ANALYSER_FILE} build phpqa

phpqa-bash:
	docker compose -f ${DOCKER_COMPOSE_ANALYSER_FILE} run --rm -it phpqa sh

#------------------------------------------------------------------------------

.PHONY: ecs-check phpmd phpqa-rebuild phpqa-bash
