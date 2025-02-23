# Changelog
All notable changes to this project documented in this file.  
Releases are following [Semantic Versioning](https://semver.org/spec/v2.0.0.html) specification.

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
