.SILENT:
.PHONY: $(MAKECMDGOALS)

test:
	$(MAKE) phpcs
	echo "\n"
	$(MAKE) phpstan
	echo "\n"
	$(MAKE) phpunit

phpcs:
	echo "\033[7m # \033[0m \033[1mCS Fixer\033[0m"
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer --ansi --show-progress=dots --diff check

phpcs-fix:
	echo "\033[7m # \033[0m \033[1mCS Fixer (fix)\033[0m"
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer --ansi --show-progress=dots --diff fix

phpstan:
	echo "\033[7m # \033[0m \033[1mPHPStan\033[0m"
	vendor/bin/phpstan --ansi --memory-limit=1G --no-progress

phpunit:
	echo "\033[7m # \033[0m \033[1mPHPUnit\033[0m"
	vendor/bin/phpunit
