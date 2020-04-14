<?php
/**
 * Created by PhpStorm.
 * User: kimmok
 * Date: 03/04/2020
 * Time: 12.26
 */

use OpMerchantServices\SDK\Request\GetTokenRequest;
use PHPUnit\Framework\TestCase;

class GetTokenRequestTest extends TestCase
{

    public function testGetTokenRequest()
    {
        $getTokenRequest = new GetTokenRequest();
        $getTokenRequest->setCheckoutTokenizationId('818c478e-5682-46bf-97fd-b9c2b93a3fcd');

        $this->assertEquals(true, $getTokenRequest->validate());
    }
}
