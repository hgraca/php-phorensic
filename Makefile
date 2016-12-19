CURRENT_BRANCH="$(shell git rev-parse --abbrev-ref HEAD)"

default: help

help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

box:
	bin/box

box-update:
	curl -LSs https://box-project.github.io/box2/installer.php | php
	chmod +x box.phar
	mv box.phar bin/box

composer-install:
	php -n -d extension=json.so -d extension=tokenizer.so -d extension=dom.so /usr/bin/composer install

composer-update:
	php -n -d extension=json.so -d extension=tokenizer.so -d extension=dom.so /usr/bin/composer update

coverage:
	php -dzend_extension=xdebug.so vendor/bin/phpunit --coverage-text --coverage-clover=coverage.clover.xml

cs-fix:
	vendor/bin/php-cs-fixer fix --verbose

test:
	vendor/bin/phpunit
	vendor/bin/humbug

test-debug:
	php -dzend_extension=xdebug.so vendor/bin/phpunit

test-acceptance:
	vendor/bin/phpunit --testsuite acceptance

test-functional:
	vendor/bin/phpunit --testsuite functional

test-humbug:
	vendor/bin/humbug

test-integration:
	vendor/bin/phpunit --testsuite integration

test-unit:
	vendor/bin/phpunit --testsuite unit
