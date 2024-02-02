#------------------------------------------------------------------------------
# Database Makefile
# 	- with doctrine
#------------------------------------------------------------------------------

DOCTRINE_DOCKER_CMD = docker compose -f ${DOCKER_COMPOSE_FILE} exec -T --user ${USER_ID} php ${1}
DATABASE_DOCKER_CMD = docker compose -f ${DOCKER_COMPOSE_FILE} exec -T db ${1}

#------------------------------------------------------------------------------

db-fixtures: ##@database run the database fixtures
	$(call DOCTRINE_DOCKER_CMD, php bin/console doctrine:fixtures:load --no-interaction)

db-migration: ##@database create a new migration file
	$(call DOCTRINE_DOCKER_CMD, php bin/console doctrine:migrations:diff)

db-schema-update:
	$(call DOCTRINE_DOCKER_CMD, php bin/console doctrine:schema:update --force)

db-dump:
	$(call DATABASE_DOCKER_CMD, /bin/bash -c "PGPASSWORD=du_FT5pqkBc_uz489ya pg_dump --username flo encuisine") > data/dump.sql

db-wait-for:
	sh bin/wait-for-db.sh

#------------------------------------------------------------------------------

.PHONY: db-init db-create db-drop db-create-migration db-schema-update db-wait-for
