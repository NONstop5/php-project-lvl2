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

cmp-stylish-json-json:
	./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json

cmp-stylish-json-yaml:
	./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.yml

cmp-plain-json-json:
	./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json --format=plain

cmp-plain-yml-yaml:
	./bin/gendiff tests/fixtures/file2.yml tests/fixtures/file1.yaml --format=plain

cmp-json-json-json:
	./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json --format=json

cmp-json-yaml-yml:
	./bin/gendiff tests/fixtures/file1.yaml tests/fixtures/file2.yml --format=json

.PHONY: tests
