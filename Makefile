install:
	composer install

validate:
	composer validate

dump:
	composer dump-autoload -o

lint:
	composer exec --verbose phpcs -- --standard=PSR12 --colors src bin

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 --colors src bin

phpstan:
	vendor/bin/phpstan analyse

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

.PHONY: tests
