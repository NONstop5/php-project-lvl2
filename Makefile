install:
	composer install

validate:
	composer validate

dump:
	composer dump-autoload -o

lint:
	composer exec -v phpcs src bin tests

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 --colors src bin tests

phpstan:
	vendor/bin/phpstan analyse

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

cmp-plain-json-json:
	./bin/gendiff tests/fixtures/plain/file1.json tests/fixtures/plain/file2.json

cmp-plain-json-yaml:
	./bin/gendiff tests/fixtures/plain/file1.json tests/fixtures/plain/file2.yaml

cmp-plain-yml-json:
	./bin/gendiff tests/fixtures/plain/file1.yml tests/fixtures/plain/file2.json

.PHONY: tests
