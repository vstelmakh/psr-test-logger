# PSR Test Logger Method Reference
[TestLogger](../src/TestLogger.php) class consists of three main parts:
1. **Logger** - an implementation of `Psr\Log\LoggerInterface` interface. Allows acting as a logger for all the places 
   where LoggerInterface is required.
2. **Assert** - fluent interface to assert log records with conditions provided by [Matcher](../src/Match/Matcher.php).
3. **Filter** - fluent interface to filter log records with conditions provided by [Matcher](../src/Match/Matcher.php).

## Logger Reference
* `log` - Logs with an arbitrary level.
* `debug` - Log with debug level.
* `info` - Log with info level.
* `notice` - Log with notice level.
* `warning` - Log with warning level.
* `error` - Log with error level.
* `critical` - Log with critical level.
* `alert` - Log with alert level.
* `emergency` - Log with emergency level.

## Assert Reference
* `hasLog` - Assert logger contains logs.
* `hasDebug` - Assert logger contains logs with level "debug".
* `hasInfo` - Assert logger contains logs with level "info".
* `hasNotice` - Assert logger contains logs with level "notice".
* `hasWarning` - Assert logger contains logs with level "warning".
* `hasError` - Assert logger contains logs with level "error".
* `hasCritical` - Assert logger contains logs with level "critical".
* `hasAlert` - Assert logger contains logs with level "alert".
* `hasEmergency` - Assert logger contains logs with level "emergency".

All of the `assert` methods returns [Matcher](../src/Match/Matcher.php), which allows specifying more assertion conditions 
by simply chaining methods from matcher. For available methods and examples see [mather reference](#matcher-reference) below.

## Filter Reference
* `getAll` - Filter all logs.
* `getDebug` - Filter logs with level "debug".
* `getInfo` - Filter logs with level "info".
* `getNotice` - Filter logs with level "notice".
* `getWarning` - Filter logs with level "warning".
* `getError` - Filter logs with level "error".
* `getCritical` - Filter logs with level "critical".
* `getAlert` - Filter logs with level "alert".
* `getEmergency` - Filter logs with level "emergency".

All of the `filter` methods returns [Matcher](../src/Match/Matcher.php), which allows specifying more filter conditions
by simply chaining methods from matcher. For available methods and examples see [mather reference](#matcher-reference) below.

## Matcher Reference
All the following methods are available for both `assert` and `filter`:
* [getLogs](#getlogs)
* [withLevel](#withlevel)
* [withMessage](#withmessage)
* [withMessageContains](#withmessagecontains)
* [withMessageContainsIgnoreCase](#withmessagecontainsignorecase)
* [withMessageStartsWith](#withmessagestartswith)
* [withMessageMatches](#withmessagematches)
* [withContextEqualTo](#withcontextequalto)
* [withContextSameAs](#withcontextsameas)
* [withContextContainsEqualTo](#withcontextcontainsequalto)
* [withContextContainsSameAs](#withcontextcontainssameas)
* [withCallback](#withcallback)

Example:
```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();

$logger->info('This is info message.');
$logger->error('This is error message.');

// Assert:
$logger->assert()->hasLog()->withMessage('This is info message.');

// Filter:
$logs = $logger->filter()->getAll()->withMessage('This is info message.')->getLogs();
```

> [!TIP]  
> The above methods could be 🔗 chained to define more strict matching criteria.

### getLogs
Return logs that match previously applied filters. Could be useful when there is a need for additional
custom assertions on filtered log messages. Returns `array<Log>` – array of [Log](../src/Log/Log.php) objects.

```php
use VStelmakh\PsrTestLogger\Log\Log;
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();

$logger->info('This is info message.');
$logger->error('This is error message.');

$logs = $logger->filter()->getError()->getLogs();

// Returns:
[
    new Log(
        level: 'error',
        message: 'This is error message.',
        context: [],
    )
]
```

### withLevel
Match logs with a specified log level.

```php
use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->info('This is info message.');
$logger->assert()->hasLog()->withLevel(LogLevel::INFO);
```

### withMessage
Match logs with a specified message.

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->info('This is info message.');
$logger->assert()->hasLog()->withMessage('This is info message.');
```

### withMessageContains
Match logs with a message containing a substring.

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->info('This is info message.');
$logger->assert()->hasLog()->withMessageContains('info message');
```

### withMessageContainsIgnoreCase
Match logs with a message contain substring (case-insensitive).

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->info('This is info message.');
$logger->assert()->hasLog()->withMessageContainsIgnoreCase('INFO message');
```

### withMessageStartsWith
Match logs with a message starting with the prefix.

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->info('This is info message.');
$logger->assert()->hasLog()->withMessageStartsWith('This is info');
```

### withMessageMatches
Match logs with a message matching regular expression.

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->info('This is info message.');
$logger->assert()->hasLog()->withMessageMatches('/(info|debug) message/');
```

### withContextEqualTo
Match logs with context as provided one, using loose comparison (==).

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->info('This is info message.', ['some' => 'data']);
$logger->assert()->hasLog()->withContextEqualTo(['some' => 'data']);
```

### withContextSameAs
Match logs with context as provided one, using strict comparison (===). Useful to check if an object is the same instance.

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$data = new \stdClass();
$logger->info('This is info message.', ['some' => $data]);
$logger->assert()->hasLog()->withContextSameAs(['some' => $data]);
```

### withContextContainsEqualTo
Match logs with context contain a key-value pair, using loose comparison (==).

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->info('This is info message.', ['some' => 'data', 'other' => 'value']);
$logger->assert()->hasLog()->withContextContainsEqualTo('some', 'data');
```

### withContextContainsSameAs
Match logs with context contain a key-value pair, using loose comparison (==). Useful to check if an object is the same instance.

```php
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$data = new \stdClass();
$logger->info('This is info message.', ['some' => $data, 'other' => 'value']);
$logger->assert()->hasLog()->withContextContainsSameAs('some', $data);
```

### withCallback
Match logs by callback. Useful for any custom assertions and filtering.

```php
use VStelmakh\PsrTestLogger\Log\Log;
use VStelmakh\PsrTestLogger\TestLogger;

$logger = new TestLogger();
$logger->error('This is error message.', ['exception' => new \RuntimeException('Error', 256)]);

$logger->assert()->hasLog()->withCallback(function (Log $log): bool {
    $exception = $log->context['exception'];
    
    if (!$exception instanceof \RuntimeException) {
        return false;
    }
    
    return $exception->getCode() === 256;
});
```
