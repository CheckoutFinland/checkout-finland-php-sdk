# Checkout Finland PHP-SDK
PHP SDK for using Checkout Finland payment service

## Requirements

### PHP

- PHP version >= 7.3

### Composer packages

- [PHPUnit](https://github.com/sebastianbergmann/phpunit) - A programmer-oriented testing framework for running unit tests in PHP.

### Library packages
- [Guzzle](https://github.com/guzzle/guzzle) v6.5.5 - PHP HTTP client for performing HTTP request.

## Installation

Install with Composer:

```
composer require checkout-finland/checkout-finland-php-sdk
```

The package uses PSR-4 autoloader. Activate autoloading by requiring the Composer autoloader:

```
require 'vendor/autoload.php';
```

_Note the path to the vendor directory is relative to your project._

## Folder contents & descriptions

| Folder/File | Content/Description |
| ------------- | ------------- |
| src/Exception  | Exception classes and functions  |
| src/Interfaces  | Interface classes and functions for all the related classes to implement  |
| src/Model  | Model classes and functions  |
| src/Request  | Request classes and functions  |
| src/Response  | Response classes and functions  |
| src/Util  | Utility/trait classes and functions  |
| src/Client.php  | Client class and functions  |
| lib | Library packages eg. Guzzle
| tests/unit  | PHP unit tests  |

## Basic functionalities

### Payments and refunds

#### Creating payment request

Documentation for creating payment requests can be found in Checkout PSP API [Checkout PSP API reference](https://checkoutfinland.github.io/psp-api/#/?id=create).

#### Creating payment status request

Documentation for creating payment status requests can be found in [Checkout PSP API reference](https://checkoutfinland.github.io/psp-api/#/?id=get)

#### Creating refund request

Documentation for creating refund requests can be found in [Checkout PSP API reference](https://checkoutfinland.github.io/psp-api/#/?id=refund)

### Tokenized credit cards and payments

#### Creating Add card form request

Documentation for adding tokenized credit card form request can be found in [Checkout PSP API reference](https://checkoutfinland.github.io/psp-api/#/?id=adding-tokenizing-cards)

#### Creating Get token request

Documentation for Get token request and response can be found in [Checkout PSP API reference](https://checkoutfinland.github.io/psp-api/#/?id=get-token)

#### Creating CIT or MIT payment requests

Documentation for Customer Initiated Transactions (CIT) and Merchant Initiated Transactions (MIT) payment processes using tokenized credit cards can be found in [Checkout PSP API reference](https://checkoutfinland.github.io/psp-api/#/?id=charging-a-token)
