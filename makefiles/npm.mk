#------------------------------------------------------------------------------
# NPM and Webpack Makefile
#------------------------------------------------------------------------------

NPM_DOCKER_CMD = docker-compose -f ${DOCKER_COMPOSE_BUILDER_FILE} run -T --user ${USER_ID}:${GROUP_ID} node npm ${1}

#------------------------------------------------------------------------------

npm-init:
	test -e ~/.npm || mkdir ~/.npm

npm-install: npm-init ##@npm install npm dependencies
	$(call NPM_DOCKER_CMD, install --silent)

#------------------------------------------------------------------------------

webpack-build: ##@npm build assets for development environment
	$(call NPM_DOCKER_CMD, run dev)

webpack-build-production: ##@npm build assets for production environment
	$(call NPM_DOCKER_CMD, run build)

webpack-watch: ##@npm run webpack watch
	$(call NPM_DOCKER_CMD, run watch)

#------------------------------------------------------------------------------

clean-npm: ##@npm clean npm dependencies
	test ! -e node_modules || rm -rf node_modules

clean-built-assets: ##@npm clean built assets
	test ! -e public/build || rm -rf public/build

#------------------------------------------------------------------------------

.PHONY: npm-install webpack-build webpack-build-production webpack-watch clean-npm clean-built-assets
