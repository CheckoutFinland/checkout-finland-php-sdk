<?php

use OpMerchantServices\SDK\Client;
use OpMerchantServices\SDK\Exception\HmacException;
use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Address;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\Customer;
use OpMerchantServices\SDK\Model\Item;
use OpMerchantServices\SDK\Request\PaymentRequest;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{

    public function testClient()
    {
        $merchantId = 375917;
        $secret = 'SAIPPUAKAUPPIAS';
        $args = [
            'timeout' => 20,
            'cofPluginVersion' => 'phpunit-test'
        ];

        $client = new Client($merchantId, $secret, $args);

        $item = (new Item())
            ->setDeliveryDate('2020-12-12')
            ->setProductCode('pr1')
            ->setVatPercentage(24)
            ->setReference('itemReference123')
            ->setStamp('itemStamp-' . rand(1, 999999))
            ->setUnits(1)
            ->setDescription('some description')
            ->setUnitPrice(100);

        $item2 = (new Item())
            ->setDeliveryDate('2020-12-12')
            ->setProductCode('pr2')
            ->setVatPercentage(24)
            ->setReference('itemReference123')
            ->setStamp('itemStamp-' . rand(1, 999999))
            ->setUnits(2)
            ->setDescription('some description2')
            ->setUnitPrice(200);

        $redirect = (new CallbackUrl())
            ->setCancel('https://somedomain.com/cancel')
            ->setSuccess('https://somedomain.com/success');

        $cb = (new CallbackUrl())
            ->setCancel('https://callbackdomain.com/cancel')
            ->setSuccess('https://callbackdomain.com/success');

        $customer = (new Customer())
            ->setEmail('customer@customerdomain.com')
        ;

        $address = (new Address())
            ->setStreetAddress('Hämeenkatu 12')
            ->setCity('Tampere')
            ->setCountry('Finland')
            ->setPostalCode('33200')
        ;

        $paymentRequest = (new PaymentRequest())
            ->setCustomer($customer)
            ->setRedirectUrls($redirect)
            ->setCallbackUrls($cb)
            ->setItems([$item, $item2])
            ->setAmount(500)
            ->setStamp('PaymentRequestStamp' . rand(1, 999999))
            ->setReference('PaymentRequestReference' . rand(1, 999999))
            ->setCurrency('EUR')
            ->setLanguage('EN')
            ->setDeliveryAddress($address)
            ->setInvoicingAddress($address)
        ;


        //$providers = $client->getPaymentProviders();
        //var_dump($providers);

        if ($paymentRequest->validate()) {
            try {
                $response = $client->createPayment($paymentRequest);

                $this->assertObjectHasAttribute('transactionId', $response);
                $this->assertObjectHasAttribute('href', $response);
                $this->assertObjectHasAttribute('providers', $response);
                $this->assertIsArray($response->getProviders());

                var_dump($response);


            } catch (HmacException $e) {
                var_dump($e->getMessage());
            } catch (ValidationException $e) {
                var_dump($e->getMessage());
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                echo 321;
                var_dump(json_decode($e->getResponse()->getBody()));
            }

        } else {
            echo 'PaymentRequest is not valid';
        }

    }

}