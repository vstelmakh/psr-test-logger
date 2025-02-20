.SILENT:

test:
	$(MAKE) phpunit

phpunit:
	echo "\033[7m # \033[0m \033[1mPHPUnit\033[0m"
	vendor/bin/phpunit
