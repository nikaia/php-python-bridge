.PHONY: *
.DEFAULT_GOAL := help

help: ## Display this help screen
	@grep -E '^[a-z.A-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

phpcsfix: ## Fix code style for all the project
	@php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php

phpcsfix-staged: ## Fix code style for staged files
	@git diff --name-only --cached --diff-filter=ACM | while read line; do \
        php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php "$$line"; \
        git add "$$line"; \
	done

setup-precommit-hook: ## Run phpcsfix-staged as precommit hook
	@if [ -f .git/hooks/pre-commit -o -L .git/hooks/pre-commit ]; then \
       echo ""; \
       echo "There is already a pre-commit hook installed."\
       exit 1; \
   fi
	@echo "#/bin/sh\n\nmake phpcsfix-staged" > .git/hooks/pre-commit
	@chmod u+x .git/hooks/pre-commit
	@echo "pre-commit hook installed."

phpstan: ## Run PHPStan analyzer
	@php vendor/bin/phpstan analyze app
