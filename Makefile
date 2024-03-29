.DEFAULT_GOAL := help

help:
	@echo ""
	@echo "Available tasks:"
	@echo "    test    Run all tests and generate coverage"
	@echo "    watch   Run all tests and coverage when a source file is upaded"
	@echo "    lint    Run only linter and code style checker"
	@echo "    unit    Run unit tests and generate coverage"
	@echo "    static  Run static analysis"
	@echo "    vendor  Install dependencies"
	@echo "    clean   Remove vendor and composer.lock"
	@echo ""

vendor: $(wildcard composer.lock)
	composer install --prefer-dist --no-interaction ${COMPOSER_ARGS}

lint: vendor
	vendor/bin/phplint . --exclude=vendor/
	vendor/bin/phpcs -p --standard=PSR2 --extensions=php --encoding=utf-8 --ignore=*/vendor/*,*/benchmarks/* .

unit: vendor
	phpdbg -qrr vendor/bin/phpunit --testdox --coverage-text --coverage-clover=coverage.xml --coverage-html=./report/

static: vendor
	vendor/bin/phpstan analyse src --level max

watch: vendor
	find . -name "*.php" -not -path "./vendor/*" -not -path "./demo/vendor/*" -o -name "*.json" -not -path "./vendor/*" | entr -c make test

test: #lint unit #static
test: unit

clean:
	rm -rf vendor
	rm composer.lock

.PHONY: help vendor lint unit static watch test clean
