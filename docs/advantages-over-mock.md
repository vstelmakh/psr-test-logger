# Advantages of PSR Test Logger over mocking LoggerInterface
Using **PSR Test Logger** library instead of creating mocks with PHPUnit offers several benefits, such as
simplified assertions, decoupling from implementation details and easier maintenance.

## Simplified Assertions
With PHPUnit mocks, asserting that logger received multiple messages can become complex, less readable and hard 
to maintain. PSR Test Logger offers straightforward methods to inspect logged messages, making tests more concise 
and easier to understand. For example, with simple method calls, you can assert that the logger has multiple specific 
messages as well as corresponding context.

## Decoupling from Implementation Details
When mocking the LoggerInterface with PHPUnit, tests may inadvertently bind to specific implementation details,
such as the choice between using the `log` method with a log level or shortcut methods like `info` or `error` etc.
Utilizing the PSR Test Logger, abstracts these details, promoting more robust and implementation-agnostic tests.

## Example
The fastest way to understand the advantages of PSR Test Logger is by implementing the test to check logging of some 
service.

### Service Class Example
Let's take a look to a simple service doing "something". Service logic is irrelevant for the current example, therefore 
it's omitted for simplicity. To monitor the execution there are log messages at the beginning and at the end of the 
process. Each log message contains user id in the context. Log message at the end of the process also contains processed 
item count.

```php
<?php

use Psr\Log\LoggerInterface

class YourService
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function doSomething(User $user): void
    {
        $this->logger->log(LogLevel::INFO, 'Starting some process.', ['user_id' => $user->id]);

        // Some process logic here...

        $this->logger->log(LogLevel::INFO, 'Some process completed successfully.', [
            'user_id' => $user->id,
            'item_count' => $count,
        ]);
    }
}
```

### Test using Mock
Implementing a test for such a service using PHPUnit mock for `LoggerInterface` leads to something like in the next
code snippet. Code is quite verbose and hard to understand. Additionally, it's highly coupled with implementation, 
explicitly defining the expectation for `log` method call.

```php
<?php

use PHPUnit\Framework\TestCase;

class YourServiceTest extends TestCase
{
    public function testWithMock(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $yourService = new YourService($logger);

        $logger
            ->expects(self::exactly(2))
            ->method('log')
            ->with(
                LogLevel::INFO,
                self::callback(function (string $message) {
                    static $i = 0;
                    $i++;

                    match ($i) {
                        1 => self::assertStringStartsWith('Starting', $message),
                        2 => self::assertStringContainsString('completed successfully', $message),
                        default => self::fail('Unexpected log message.')
                    };

                    return true;
                }),
                self::callback(function (array $context) {
                    static $i = 0;
                    $i++;

                    match ($i) {
                        1 => self::assertSame(['user_id' => 1], $context),
                        2 => self::assertSame(['user_id' => 1, 'item_count' => 3], $context),
                        default => self::fail('Unexpected context.')
                    };

                    return true;
                }),
            );

        $user = new User(id: 1);
        $yourService->doSomething($user);
    }
}
```

### Test using PSR Test Logger
While using the `TestLogger`, the corresponding test is much more compact, easy to follow and maintain. Fluent interface
makes assertions simple and clear.

```php
<?php

use PHPUnit\Framework\TestCase;
use VStelmakh\PsrTestLogger\TestLogger;

class YourServiceTest extends TestCase
{
    public function testWithTestLogger(): void
    {
        $logger = new TestLogger();
        $yourService = new YourService($logger);

        $user = new User(id: 1);
        $yourService->doSomething($user);

        $logger
            ->assert()
            ->hasInfo()
            ->withMessageStartsWith('Starting')
            ->withContextContainsSameAs('user_id', 1);

        $logger
            ->assert()
            ->hasInfo()
            ->withMessageContains('completed successfully')
            ->withContextSameAs(['user_id' => 1, 'item_count' => 3]);
    }
}
```
