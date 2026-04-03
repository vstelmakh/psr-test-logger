# Changelog
All notable changes to this project are documented in this file.  
Releases are following [Semantic Versioning](https://semver.org/spec/v2.0.0.html) specification.

### v1.1.0

Changes:
- Added negative log assertions to `assert` including:
  - `hasNoLog`
  - `hasNoDebug`
  - `hasNoInfo`
  - `hasNoNotice`
  - `hasNoWarning`
  - `hasNoError`
  - `hasNoCritical`
  - `hasNoAlert`
  - `hasNoEmergency`

Example:
```php
$logger
    ->assert()
    ->hasNoWarning()
    ->withMessageContains('not found');
```

### v1.0.1

Changes:
- Added context formatter to make context-related assertion messages more verbose.

Example:
```php
$logger
    ->assert()
    ->hasLog()
    ->withContextEqualTo([
        'description' => 'The number π is a mathematical constant',
        'pi' => 3.14159,
        'is_rational_number' => false,
    ]);
```

Message before:
> Failed asserting that has logs matching context equal to [description: **{string}**, pi: **{double}**, is_rational_number: **{boolean}**].

Message now:
> Failed asserting that has logs matching context equal to [description: **The number π is a mathematical constant**, pi: **3.14159**, is_rational_number: **false**].

### v1.0.0

Changes:
- Initial logger release implementing PSR-3 interface.
- Added `assert` and `filter` including next matchers:
  - `withLevel`
  - `withMessage`
  - `withMessageContains`
  - `withMessageContainsIgnoreCase`
  - `withMessageStartsWith`
  - `withMessageMatches`
  - `withContextEqualTo`
  - `withContextSameAs`
  - `withContextContainsEqualTo`
  - `withContextContainsSameAs`
  - `withCallback`
