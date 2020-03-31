# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.3] - 2020-03-30

### Added
- `getGroupedPaymentProviders` method for getting grouped payment providers with terms

## [1.0.2] - 2020-01-08

### Changed
- Plugin version is now a mandatory parameter and needs to be passed via Client constructor.
- All validation is done now via filter_var() and empty() to prevent problems with certain hosting providers that don't have MBString enabled.

### Added
- PHPUnit suite and tests

### Removed
- Removed validation library.


