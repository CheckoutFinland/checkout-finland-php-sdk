# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5] - 2021-09-24

- Move Guzzle dependency to lib and namespace for fixing conflicts between platforms

## [1.4] - 2021-06-14

### Changed

- Rebranding from "OP Payment Service" to "Checkout Finland" 

## [1.3.4] - 2021-02-17

### Changed

- Update minimum PHP version to 7.3 

## [1.3.3] - 2021-02-16

### Changed

- Update PHP requirement

## [1.3.2] - 2020-08-21

### Changed

- PHP 7.4 support added

## [1.3.1] - 2020-07-27

### Changed

- Change composer.json to require guzzlehttp/guzzle version 6.5.2

## [1.3.0] - 2020-06-12

### Added

- Add new interface implementations for PaymentRequest, CallbackUrl, Customer, Address, Item and TokenPaymentRequest

### Changed

- Update getGroupedPaymentProviders method request params
- Update getGroupedPaymentProviders method PHP Doc
- Update README.md

### Fixed

- Fix locale parameter is not used if amount is null -issue in getGroupedPaymentProviders function

## [1.2.1] - 2020-05-27

### Changed

- Change composer.json to require guzzlehttp/guzzle version 6.5.4

## [1.2.0] - 2020-05-26

### Changed

- Add tokenized card payments functionalities (AddCardForm, GetToken, CIT/MIT Payment requests and responses)

## [1.1.1] - 2020-04-24

### Changed

- Change composer.json to require guzzlehttp/guzzle version 6.5.3

## [1.1.0] - 2020-03-30

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


