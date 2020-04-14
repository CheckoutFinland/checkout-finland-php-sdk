<?php

use GuzzleHttp\Exception\RequestException;
use OpMerchantServices\SDK\Client;
use OpMerchantServices\SDK\Exception\HmacException;
use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Address;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Model\Customer;
use OpMerchantServices\SDK\Model\Item;
use OpMerchantServices\SDK\Request\AddCardFormRequest;
use OpMerchantServices\SDK\Request\GetTokenRequest;
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
            ->setEmail('customer@customerdomain.com');

        $address = (new Address())
            ->setStreetAddress('Hämeenkatu 12')
            ->setCity('Tampere')
            ->setCountry('Finland')
            ->setPostalCode('33200');

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
            ->setInvoicingAddress($address);

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

    public function testAddCardFormRequest()
    {
        $args = ['timeout' => 20];

        $datetime = new \DateTime();
        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION, $args);

        $addCardFormRequest = (new AddCardFormRequest())
            ->setCheckoutAccount(self::MERCHANT_ID)
            ->setCheckoutAlgorithm('sha256')
            ->setCheckoutMethod('POST')
            ->setCheckoutNonce(uniqid(true))
            ->setCheckoutTimestamp($datetime->format('Y-m-d\TH:i:s.u\Z'))
            ->setCheckoutRedirectSuccessUrl('https://somedomain.com/success')
            ->setCheckoutRedirectCancelUrl('https://somedomain.com/cancel')
            ->setLanguage('EN')
            ->setSignature('d902e82ee61cb2c6ff2ba48b255402eb5d446c943e8ebbb3ada4fe40be7b8ab5');

            $this->assertTrue($addCardFormRequest->validate());
            $response = $client->createAddCardFormRequest($addCardFormRequest);

            $this->assertEquals(302, $response->getStatusCode());
    }

    public function testGetTokenRequest()
    {
        $args = ['timeout' => 20];
        $checkoutTokenizationId = '818c478e-5682-46bf-97fd-b9c2b93a3fcd';

        $client = new Client(self::MERCHANT_ID, self::SECRET, self::COF_PLUGIN_VERSION, $args);

        $getTokenRequest = (new GetTokenRequest())
            ->setCheckoutTokenizationId($checkoutTokenizationId);

        $this->assertTrue($getTokenRequest->validate());
        $response = $client->createGetTokenRequest($getTokenRequest);

        $responseJsonData = $response->jsonSerialize();

        $expectedArray = [
            'token' => 'c7441208-c2a1-4a10-8eb6-458bd8eaa64f',
            'card' => [
                'type' => 'Visa',
                'bin' => '415301',
                'partial_pan' => '0024',
                'expire_year' => '2023',
                'expire_month' => '11',
                'cvc_required' => 'no',
                'funding' => 'debit',
                'category' => 'unknown',
                'country_code' => 'FI',
                'pan_fingerprint' => '693a68deec6d6fa363c72108f8d656d4fd0b6765f5457dd1c139523f4daaafce',
                'card_fingerprint' => 'c34cdd1952deb81734c012fbb11eabc56c4d61d198f28b448327ccf13f45417f'
            ],
            'customer' => [
                'network_address' => '93.174.192.154',
                'country_code' => 'FI'
            ]
        ];

        $this->assertObjectHasAttribute('token', $response);
        $this->assertObjectHasAttribute('card', $response);
        $this->assertObjectHasAttribute('customer', $response);
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($responseJsonData));
    }
}
