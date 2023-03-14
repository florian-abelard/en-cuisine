#------------------------------------------------------------------------------
# Composer Makefile
#------------------------------------------------------------------------------

COMPOSER_DOCKER_CMD = docker-compose run -T --user ${USER_ID}:${GROUP_ID} php composer ${1}

#------------------------------------------------------------------------------

composer-init:
	@mkdir -p ~/.cache/composer

composer-install: composer-init ##@composer install composer dependencies
	$(call COMPOSER_DOCKER_CMD, install --no-progress --prefer-dist --optimize-autoloader)

composer-dump-autoload: composer-init ##@composer dump autoloading
	$(call COMPOSER_DOCKER_CMD, dump-autoload)

#------------------------------------------------------------------------------

clean-composer:##@composer delete vendor directory
	test ! -e vendor || rm -r vendor

#------------------------------------------------------------------------------

.PHONY: composer-install composer-dump-autoload clean-composer
