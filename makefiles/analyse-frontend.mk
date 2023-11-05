#------------------------------------------------------------------------------
# Analyse frontend Makefile
#------------------------------------------------------------------------------

lint: ##@eslint run eslint on the specified folder
	docker compose exec node npm run lint

lint-fix: ##@eslint run eslint on the specified folder
	docker compose exec node npm run lint-fix

#------------------------------------------------------------------------------

.PHONY: lint lint-fix
