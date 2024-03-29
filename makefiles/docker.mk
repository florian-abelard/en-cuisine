#------------------------------------------------------------------------------
# Docker Makefile
#------------------------------------------------------------------------------

DOCKER_CMD = docker compose exec -T --user ${USER_ID}:${GROUP_ID} ${1}

# Cli arguments
ifneq (,$(filter cmd-php cmd-node, $(firstword $(MAKECMDGOALS))))
    CLI_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    CLI_ARGS := $(subst :,\:,$(CLI_ARGS))
    $(foreach arg,$(CLI_ARGS),$(eval $(arg):;@true))
endif

#------------------------------------------------------------------------------

cmd-php: ##@docker run the specified command in the php container | usage: make cmd-php -- COMMAND
	@echo ${CLI_ARGS}
	@$(call DOCKER_CMD, php $(CLI_ARGS))

cmd-node: ##@docker run the specified command in the php container | usage: make cmd-node -- COMMAND
	@echo ${CLI_ARGS}
	@$(call DOCKER_CMD, node $(CLI_ARGS))

#------------------------------------------------------------------------------

build: ##@docker build containers
	docker compose -f ${DOCKER_COMPOSE_FILE} build

rebuild: build up ##@docker rebuild and start containers

clean: down ##@docker clean docker containers
	docker container ls -a | grep "${APP_NAME}" | awk '{print $1}' | xargs --no-run-if-empty docker container rm

#------------------------------------------------------------------------------

.PHONY: cmd-php build rebuild clean

