<?php
/**
 * The checkout form view.
 *
 * The payment transaction is created on page load.
 *
 * The user selects the payment provider which takes the user to complete the payment transaction.
 */

use CheckoutFinland\SDK\Client;
use CheckoutFinland\SDK\Exception\HmacException;
use GuzzleHttp\Exception\RequestException;

// Create SDK client instance
$client = new Client(
    375917,
    'SAIPPUAKAUPPIAS',
    [
        'cofPluginVersion' => 'php-sdk-test-1.0.0',
    ]
);

$firstName = filter_input( INPUT_POST, 'first-name', FILTER_SANITIZE_STRING );
$lastName = filter_input( INPUT_POST, 'last-name', FILTER_SANITIZE_STRING );
$amount = (int) filter_input( INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT );
$address = filter_input( INPUT_POST, 'address', FILTER_SANITIZE_STRING );
$postalCode = filter_input( INPUT_POST, 'postal-code', FILTER_SANITIZE_STRING );
$city = filter_input( INPUT_POST, 'city', FILTER_SANITIZE_STRING );
$country = filter_input( INPUT_POST, 'country', FILTER_SANITIZE_STRING );

// TODO: Create payment.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout | OP Payment Service Example</title>
</head>
<body>
    <h1>Checkout</h1>

    <form method="post" action="checkout.php">
        <fieldset>
            <legend>Select the payment provider</legend>
            TODO: print out the provider forms.
        </fieldset>
    </form>
</body>
</html>