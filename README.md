<img src="./resources/psr-test-logger-logo.svg" width="301" height="179" alt="PSR Test Logger logo">

---

[![build](https://github.com/vstelmakh/psr-test-logger/actions/workflows/build.yml/badge.svg)](https://github.com/vstelmakh/psr-test-logger/actions/workflows/build.yml)

# PSR Test Logger
PSR Test Logger is a simple and easy-to-use [PSR-3](https://www.php-fig.org/psr/psr-3/) compliant logger designed specifically for testing.
It provides seamless integration with [PHPUnit](https://phpunit.de/), making logging assertions effortless.
With PSR Test Logger, you can efficiently verify log messages and context, ensuring your application logs expected events without unnecessary complexity.

Key features:
- **Fluent Interface** - Enables cleaner test assertions and better IDE autocompletion.
- **Predefined Assertions** - A rich set of built-in assertions to validate log messages and contexts.
- **Automatic PHPUnit Integration** - Works seamlessly within PHPUnit test cases, with zero configuration.
- **Extensible** - Easily customizable to fit your testing needs.

## Requirements
| Requirement | Version |
|-------------|---------|
| PHP         | >= 8.1  |
| psr/log     | >= 2.0  |

> [!NOTE]  
> PHPUnit is not required to use PSR Test Logger. It can be used completely standalone.

## Installation
Install the latest version with [Composer](https://getcomposer.org/):

```bash
composer require --dev vstelmakh/psr-test-logger
```

Remember to require as `dev` dependency. Most likely, you don't need this in production.

## Usage example
Using PSR Test Logger is as simple as following code example:

```php
<?php

use VStelmakh\PsrTestLogger\TestLogger;
use PHPUnit\Framework\TestCase;

class YourServiceTest extends TestCase
{
    public function testSomething(): void
    {
        $logger = new TestLogger();
        $service = new YourService($logger);
        $service->doSomething();

        $logger->assert()->hasInfo()->withMessage('Execution complete.');
        $logger->assert()->hasWarning()->withMessageContains('not found')->withContextContains('id', 1);

        // That's it!
        // Thanks to automatic PHPUnit integration corresponding assertations will be performed.
    }
}
```

> [!TIP]  
> For all the available methods check [TestLogger](src/TestLogger.php) and [Matcher](src/Match/Matcher.php), it's well documented and easy to understand.  
> Or simply use ⚡ autocompletion of your IDE!

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  
