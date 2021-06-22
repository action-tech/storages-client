init: copy-config composer-install

down:
	docker-compose down --remove-orphans

down-force:
	docker-compose down --remove-orphans -v

copy-config:
	./build/init-copy-config.sh

composer-install:
	docker-compose run app composer install

lint: lint-php-syntax lint-yaml lint-php-static lint-php-style

lint-php-syntax:
	docker-compose run --rm linter bash -c "find . -type d \( -path ./vendor -o -path ./var/cache -o \) -prune -o -name \"*.php\" -print0 | xargs -0 -n1 -P8 php -l"
lint-yaml:
	docker run --rm -v "${PWD}":/app sdesbure/yamllint yamllint -c /app/.yamllint /app/
lint-php-static:
	docker-compose run --rm linter psalm
lint-php-style:
	docker-compose run --rm linter phpcs --standard=psr12 --warning-severity=0 ./src
fix-php-style:
	docker-compose run linter phpcbf --standard=psr12 ./src

test:
	docker-compose run --rm test ./vendor/bin/phpunit --coverage-text