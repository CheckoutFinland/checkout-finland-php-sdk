<?php
/**
 * The checkout form view.
 *
 * The payment transaction is created on page load.
 *
 * The user selects the payment provider which takes the user to complete the payment transaction.
 */

require_once('examples/payment/Providers.php');
require_once('examples/payment/Payment.php');

$data = [
    'email' => filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING ),
    'firstName' => filter_input( INPUT_POST, 'first-name', FILTER_SANITIZE_STRING ),
    'lastName' => filter_input( INPUT_POST, 'last-name', FILTER_SANITIZE_STRING ),
    'phone' => (int) filter_input( INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT ),
    'amount' => (int) filter_input( INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT ),
    'address' => filter_input( INPUT_POST, 'address', FILTER_SANITIZE_STRING ),
    'postalCode' => filter_input( INPUT_POST, 'postal-code', FILTER_SANITIZE_STRING ),
    'city' => filter_input( INPUT_POST, 'city', FILTER_SANITIZE_STRING ),
    'country' => filter_input( INPUT_POST, 'country', FILTER_SANITIZE_STRING ),
    'county' => filter_input( INPUT_POST, 'county', FILTER_SANITIZE_STRING )
];

if ($data['county'] != '') {

    $payment = new Payment();

    $providers = new Providers();

    $paymentData = $payment->processPayment($data);

    if (isset($paymentData['error'])) {
        echo '<h3>An error has occurred: ' . $paymentData['error'] . '</h3>';
        die();
    }

    $paymentData = $paymentData['data'];

    $payProviders = $paymentData->getProviders();

    $arr = array();
    foreach ($payProviders as $key => $item) {
//        $url = $item->getUrl();
//        $formFields = [];
//        foreach ($item->getParameters() as $parameter) {
//            $formFields[$parameter->name] = $parameter->value;
//        }
//
//        $options = array(
//            'http' => array(
//                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//                'method'  => 'POST',
//                'content' => http_build_query($formFields)
//            )
//        );
//
//        stream_context_set_default($options);
//        $headers = get_headers($url, 1);
//
//        var_dump($headers);

        $arr[$item->getGroup()][$key] = $item;
    }

    $groupData = $providers->getProviderGroupData($data['amount'] * 100, $data['country']);

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Checkout | OP Payment Service Example</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/handleMethodClick.js"></script>
        <script>
            function redirect(link) {
                window.open(link, "mywindow");
            }
        </script>
    </head>
    <body>
    <h1>Checkout</h1>

    <form id="methods">
        <?php

//        if(isset($_POST[$pivo])) {
        $url = $arr['mobile'][1]->getUrl();
        $formFields = [];

        foreach ($arr['mobile'][1]->getParameters() as $parameter) {
            $formFields[$parameter->name] = $parameter->value;
        }

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($formFields)
            )
        );

        stream_context_set_default($options);
        $headers = get_headers($url, 1);
//        header('Location: ' . $headers['Location']);
//        }
        echo "<input type='submit' name='pivo' value='Pivo' data-url='" . $url . "' data-data='" . json_encode($formFields) . "'/>"

        ?>

        <fieldset>
            <legend>Select the payment provider</legend>
            <?php

            echo '<a href="' . $paymentData->getHref() . '" target="_blank">OP Payment Service</a>';

            $terms_link = $groupData['terms'];
            echo '<div class="checkout-terms-link">' . $terms_link . '</div>';
            echo '<div class="container-fluid ml-0">';

            foreach ($arr as $group){

                $id = reset($group)->getGroup();

                echo '<h5 class="text-capitalize mt-4">' . $id . '</h5>';
                echo '<div class="group-' . $id . ' row">';

                foreach ($group as $provider) {

                    $params = json_encode($provider->getParameters());
                    $link = $provider->getUrl() . "?parameters=" . $params;

                    echo "<div class='" . $provider->getName() . " border m-2' onclick='redirect(" . json_encode($link) . ");'>";
                    echo "<img src='" . $provider->getIcon() . "' class='provider-logo'>";
                    echo "<div class='provider-name d-none'>" . $provider->getName();
                    echo "</div>";
                    echo "</div>";
                }
                echo '</div>';
            }
            echo '</div>';

                ?>
        </fieldset>
    </form>
    </body>
    </html>
<?php
} else {
    echo 'Please fill all form fields!';
}
?>