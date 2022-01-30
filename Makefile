install:
	composer install

validate:
	composer validate

dump:
	composer dump-autoload -o

lint:
	composer exec --verbose phpcs -- --standard=PSR12 --colors src bin

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

.PHONY: tests
