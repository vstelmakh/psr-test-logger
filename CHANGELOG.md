# Changelog
All notable changes to this project documented in this file.  
Releases are following [Semantic Versioning](https://semver.org/spec/v2.0.0.html) specification.

### v1.0.1
Date: `2025-03-15`

Changes:
- Added context formatter to make context related assertion messages more verbose.

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
Date: `2025-02-23`

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
