<?php

use GuzzleHttp\Exception\RequestException;
use OpMerchantServices\SDK\Client;
use OpMerchantServices\SDK\Exception\HmacException;
use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Address;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\Customer;
use OpMerchantServices\SDK\Model\Item;
use OpMerchantServices\SDK\Request\PaymentRequest;
use OpMerchantServices\SDK\Request\PaymentStatusRequest;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    const SECRET = 'SAIPPUAKAUPPIAS';

    const MERCHANT_ID = 375917;

    const COF_PLUGIN_VERSION = 'phpunit-test';

    public function testPaymentRequest()
    {
        $args = ['timeout' => 20];

        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION, $args);

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

        $transactionId = '';

        if ($paymentRequest->validate()) {
            try {
                $response = $client->createPayment($paymentRequest);

                $this->assertObjectHasAttribute('transactionId', $response);
                $this->assertObjectHasAttribute('href', $response);
                $this->assertObjectHasAttribute('providers', $response);
                $this->assertIsArray($response->getProviders());

                $transactionId = $response->getTransactionId();

                //var_dump($response);
            } catch (HmacException $e) {
                var_dump($e->getMessage());
            } catch (ValidationException $e) {
                var_dump($e->getMessage());
            } catch (RequestException $e) {
                var_dump(json_decode($e->getResponse()->getBody()));
            }

        } else {
            echo 'PaymentRequest is not valid';
        }

        // Test payment status request with the transactionId we got from the PaymentRequest
        $psr = new PaymentStatusRequest();
        $psr->setTransactionId($transactionId);

        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION);

        try {
            $res = $client->getPaymentStatus($psr);
            $this->assertEquals('new', $res->getStatus());
            $this->assertEquals($res->getTransactionId(), $transactionId);
        } catch (HmacException $e) {
            var_dump('hmac error');
        } catch (ValidationException $e) {
            var_dump('validation error');
        }
    }
}
