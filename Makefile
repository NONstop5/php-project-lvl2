install:
	composer install

validate:
	composer validate

dump:
	composer dump-autoload -o

lint:
	composer exec -v phpcs src bin tests

lint-fix:
	composer exec -v phpcbf -- --standard=PSR12 --colors src bin tests

phpstan:
	vendor/bin/phpstan analyse

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer test:coverage

test-coverage-text:
	composer test:coverage-text

test-coverage-html:
	composer test:coverage-html

.PHONY: tests
