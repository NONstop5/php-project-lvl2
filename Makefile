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

cmp-plain-json-json:
	./bin/gendiff tests/fixtures/plain/file1.json tests/fixtures/plain/file2.json

cmp-plain-json-yaml:
	./bin/gendiff tests/fixtures/plain/file1.json tests/fixtures/plain/file2.yaml

cmp-plain-yml-json:
	./bin/gendiff tests/fixtures/plain/file1.yml tests/fixtures/plain/file2.json

cmp-plain-yaml-yml:
	./bin/gendiff tests/fixtures/plain/file2.yaml tests/fixtures/plain/file1.yml

cmp-nested-json-json:
	./bin/gendiff tests/fixtures/nested/file1.json tests/fixtures/plain/file2.json

cmp-nested-json-yaml:
	./bin/gendiff tests/fixtures/nested/file1.json tests/fixtures/plain/file2.yaml

cmp-nested-yml-json:
	./bin/gendiff tests/fixtures/nested/file1.yml tests/fixtures/plain/file2.json

cmp-nested-yaml-yml:
	./bin/gendiff tests/fixtures/nested/file2.yaml tests/fixtures/plain/file1.yml

.PHONY: tests
