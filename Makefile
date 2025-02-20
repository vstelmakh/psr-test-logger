.SILENT:
.PHONY: $(MAKECMDGOALS)

test:
	$(MAKE) phpcs-check
	echo "\n"
	$(MAKE) phpstan
	echo "\n"
	$(MAKE) phpunit

phpcs:
	echo "\033[7m # \033[0m \033[1mCS Fixer\033[0m"
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer --ansi $(filter-out $@,$(MAKECMDGOALS))

phpcs-check:
	$(MAKE) -- phpcs check --show-progress=dots --diff

phpcs-fix:
	$(MAKE) -- phpcs fix --show-progress=dots --diff

phpstan:
	echo "\033[7m # \033[0m \033[1mPHPStan\033[0m"
	vendor/bin/phpstan --ansi --memory-limit=1G

phpunit:
	echo "\033[7m # \033[0m \033[1mPHPUnit\033[0m"
	vendor/bin/phpunit
