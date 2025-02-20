.SILENT:
.PHONY: $(MAKECMDGOALS)

test:
	$(MAKE) phpcs-check
	echo "\n"
	$(MAKE) phpunit

phpunit:
	echo "\033[7m # \033[0m \033[1mPHPUnit\033[0m"
	vendor/bin/phpunit

phpcs:
	echo "\033[7m # \033[0m \033[1mCS Fixer\033[0m"
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer --show-progress=dots --diff --ansi $(filter-out $@,$(MAKECMDGOALS))

phpcs-check:
	echo "\033[7m # \033[0m \033[1mCS Fixer\033[0m"
	$(MAKE) phpcs check

phpcs-fix:
	echo "\033[7m # \033[0m \033[1mCS Fixer\033[0m"
	$(MAKE) phpcs fix
