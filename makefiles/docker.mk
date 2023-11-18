#------------------------------------------------------------------------------
# Docker Makefile
#------------------------------------------------------------------------------

DOCKER_PHP_CMD = docker compose exec -T --user ${USER_ID}:${GROUP_ID} php ${1}

# Cli arguments
ifneq (,$(filter cmd-%, $(firstword $(MAKECMDGOALS))))
    CLI_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    $(eval $(CLI_ARGS):;@:)
endif

#------------------------------------------------------------------------------

cmd-php: ##@docker run the specified command in the php container | usage: make cmd-php -- COMMAND
	@echo ${CLI_ARGS}
	@$(call DOCKER_PHP_CMD, $(CLI_ARGS))

#------------------------------------------------------------------------------

.PHONY: cmd-php

