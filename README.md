# Checkout Finland PHP SDK

This is the official PHP SDK for using Checkout Finland's PSP API.

## Requirements

### PHP

- PHP version >= 7.1

### Composer packages

- [Guzzle](https://github.com/guzzle/guzzle) - PHP HTTP client for performing HTTP request.
- [Validation](https://github.com/Respect/Validation) - A validation engine for validating data before making API requests.

## Installation

Install with Composer:

```
composer require checkoutfinland/sdk
```

The package uses PSR-4 autoloader. Activate autoloading by requiring the Composer autoloader:

```
require 'vendor/autoload.php';
```

_Note the path to the vendor directory is relative to your project._

## Usage

### Creating a payment

The full documentation for creating payment requests can be found in [Checkout Finland API Refence](https://checkoutfinland.github.io/psp-api/?id=create#/?id=create).

A PHP example of the payment process can be found in [/examples/payment/index.php](./examples/payment/index.php).