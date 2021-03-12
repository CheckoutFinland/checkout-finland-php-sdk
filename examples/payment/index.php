<?php
 /**
 * An example of a cart page.
 */
require 'vendor/autoload.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OP Payment Service Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style> div {max-width: 1000px;}</style>
</head>
<body>
    <h1>OP Payment Service Example</h1>

    <form method="post" action="index.php">
        <fieldset>
            <legend>Fill in your payment information</legend>

                <div>
                    <label for="email">Email address</label>
                    <input type="text" name="email" value="maija.meikalainen@example.com"/>
                </div>

                <div>
                    <label for="first-name">First name</label>
                    <input type="text" name="first-name" value="Maija"/>
                </div>

                <div>
                    <label for="last-name">Last name</label>
                    <input type="text" name="last-name" value="Meikäläinen" />
                </div>

                <div>
                    <label for="phone">Telephone</label>
                    <input type="number" name="phone" value="0400123123" />
                </div>

                <div>
                    <label for="amount">Amount €</label>
                    <input type="number" name="amount" value="10" />
                </div>

                <div>
                    <label for="address">Street address</label>
                    <input type="text" name="address" value="Hämeenkatu 1" />
                </div>

                <div>
                    <label for="postal-code">Postal code</label>
                    <input type="text" name="postal-code" value="33100" />
                </div>

                <div>
                    <label for="city">City</label>
                    <input type="text" name="city" value="Tampere" />
                </div>

                <div>
                    <label for="country">Country</label>
                    <select id="country" name="country">
                        <option value="FI">Finland</option>
                        <option value="EN">Other</option>
                        <option value="SV">Sweden</option>
                    </select>
                </div>

                <div>
                    <label for="county">County</label>
                    <input type="text" name="county" placeholder="Uusimaa" value="" />
                </div>

            <input id='btn' name="submit" type='submit' value='Submit'>
        </fieldset>
    </form>
    <?php
    include "checkout.php";
    ?>
</body>
</html>