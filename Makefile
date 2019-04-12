install:
	composer install
self-update:
	composer self-update
lint:
	composer run-script phpcs -- --standard=PSR12 src tests
test:
	composer run-script phpunit tests