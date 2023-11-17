#------------------------------------------------------------------------------
# Database Makefile
# 	- with doctrine
#------------------------------------------------------------------------------

DATABASE_DOCKER_CMD = docker compose -f ${DOCKER_COMPOSE_FILE} exec -T --user ${USER_ID} php ${1}

#------------------------------------------------------------------------------

db-fixtures: ##@database run the database fixtures
	$(call DATABASE_DOCKER_CMD, php bin/console doctrine:fixtures:load --no-interaction)

db-migration: ##@database create a new migration file
	$(call DATABASE_DOCKER_CMD, php bin/console doctrine:migrations:diff)

db-schema-update:
	$(call DATABASE_DOCKER_CMD, php bin/console doctrine:schema:update --force)

db-wait-for:
	sh bin/wait-for-db.sh

#------------------------------------------------------------------------------

clean-db: db-drop ##@database clean database

#------------------------------------------------------------------------------

.PHONY: db-init db-create db-drop db-create-migration db-schema-update db-wait-for clean-db
