#------------------------------------------------------------------------------
# Docker Makefile
#------------------------------------------------------------------------------

DOCKER_CMD = docker compose exec -T --user ${USER_ID}:${GROUP_ID} ${1}

# Cli arguments
ifneq (,$(filter cmd-php, $(firstword $(MAKECMDGOALS))))
    CLI_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
	CLI_ARGS := $(subst :,\:,$(CLI_ARGS))
    $(eval $(CLI_ARGS):;@true)
endif

#------------------------------------------------------------------------------

cmd-php: ##@docker run the specified command in the php container | usage: make cmd-php -- COMMAND
	@echo ${CLI_ARGS}
	@$(call DOCKER_CMD, php $(CLI_ARGS))

#------------------------------------------------------------------------------

.PHONY: cmd-php

