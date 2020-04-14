<?php

use OpMerchantServices\SDK\Model\Token\Customer;
use PHPUnit\Framework\TestCase;

class TokenCustomerTest extends TestCase
{
    public function testTokenCustomer()
    {
        $customer = new Customer();
        $customer->setNetworkAddress('93.174.192.154');
        $customer->setCountryCode('FI');

        $jsonData = $customer->jsonSerialize();

        $expectedArray = [
            'network_address' => '93.174.192.154',
            'country_code' => 'FI'
        ];

        $this->assertEquals(true, $customer->validate());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($jsonData));
    }
}
