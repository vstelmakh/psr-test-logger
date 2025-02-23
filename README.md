<img src="./resources/psr-test-logger-logo.svg" width="301" height="179" alt="PSR Test Logger logo">

---

[![build](https://github.com/vstelmakh/psr-test-logger/actions/workflows/build.yml/badge.svg?branch=main)](https://github.com/vstelmakh/psr-test-logger/actions/workflows/build.yml)
[![Packagist version](https://img.shields.io/packagist/v/vstelmakh/psr-test-logger?color=orange)](https://packagist.org/packages/vstelmakh/psr-test-logger)

# PSR Test Logger
PSR Test Logger is a simple and easy-to-use [PSR-3](https://www.php-fig.org/psr/psr-3/) compliant logger designed specifically for testing.
It provides seamless integration with [PHPUnit](https://phpunit.de/), making logging assertions effortless.
With PSR Test Logger, you can efficiently verify log messages and context, ensuring your application logs expected events without unnecessary complexity.

Key features:
- **Fluent Interface** - Provides clean test assertions with intuitive IDE autocompletion.
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

## Usage Example
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

        // Thanks to automatic PHPUnit integration,
        // corresponding assertations can be performed as simple as:

        $logger->assert()
            ->hasLog()
            ->withMessage('Execution complete.');

        $logger->assert()
            ->hasWarning()
            ->withMessageContains('not found')
            ->withContextContainsSameAs('id', 1);
    }
}
```

> [!TIP]  
> For all the available methods check [TestLogger](src/TestLogger.php) and [Matcher](src/Match/Matcher.php), it's well documented and easy to follow.  
> Or simply use ⚡ autocompletion of your IDE!

## Contributing and Support
If you find this useful, don't hesitate to ⭐ give it a star!  
Contributions are welcome. Please check out the [CONTRIBUTING](CONTRIBUTING.md) for guidelines.

## Credits
[Volodymyr Stelmakh](https://github.com/vstelmakh)  
Licensed under the MIT License. See [LICENSE](LICENSE) for more information.  
