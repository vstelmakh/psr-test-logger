# PSR Test Logger
PSR Test Logger is a simple and easy-to-use [PSR-3](https://www.php-fig.org/psr/psr-3/) compliant logger designed specifically for testing.
It provides seamless integration with [PHPUnit](https://phpunit.de/), making logging assertions effortless.

Key features:
- **Fluent Interface** - Enables cleaner test assertions and better IDE autocompletion.
- **Predefined Assertions** - A rich set of built-in assertions to validate log messages and contexts.
- **Automatic PHPUnit Integration** - Works seamlessly within PHPUnit test cases, with zero configuration.
- **Extensible** - Easily customizable to fit your testing needs.

## Installation
Install the latest version with [Composer](https://getcomposer.org/):
```bash
composer require --dev vstelmakh/psr-test-logger
```

## Usage
```php
<?php

use VStelmakh\PsrTestLogger\TestLogger;
use PHPUnit\Framework\TestCase;

class YourServiceTest extends TestCase
{
    public function testSomething(): void
    {
        $logger = new TestLogger();
        $instance = new YourService($logger);
        $instance->execute();
        
        $logger->assert()->hasInfo()->withMessage('Execution complete.');
        $logger->assert()->hasWarning()->withMessageContains('not found')->withContextContains('id', 1);
    }
}
```

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  
