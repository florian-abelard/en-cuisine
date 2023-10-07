#------------------------------------------------------------------------------
# NPM and Webpack Makefile
#------------------------------------------------------------------------------

NPM_DOCKER_CMD = docker compose -f ${DOCKER_COMPOSE_FILE} exec -T --user ${USER_ID}:${GROUP_ID} node npm ${1}

#------------------------------------------------------------------------------

npm-init:
	test -e ~/.npm || mkdir ~/.npm

npm-install: npm-init ##@npm install npm dependencies
	$(call NPM_DOCKER_CMD, install --silent)

#------------------------------------------------------------------------------

clean-npm: ##@npm clean npm dependencies
	test ! -e node_modules || rm -rf node_modules

#------------------------------------------------------------------------------

.PHONY: npm-install clean-npm
