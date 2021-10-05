<?php

use PHPUnit\Framework\TestCase;
use OpMerchantServices\SDK\Model\Item;
use OpMerchantServices\SDK\Model\Customer;
use OpMerchantServices\SDK\Model\CallbackUrl;
use OpMerchantServices\SDK\Exception\ValidationException;
use OpMerchantServices\SDK\Model\Commission;
use OpMerchantServices\SDK\Request\ShopInShopPaymentRequest;

class ShopInShopPaymentRequestTest extends TestCase
{
    public function testShopInShopPaymentRequest()
    {
        $r = new ShopInShopPaymentRequest();
        $r->setAmount(30);
        $r->setStamp('RequestStamp');
        $r->setReference('RequestReference123');
        $r->setCurrency('EUR');
        $r->setLanguage('EN');

        $com1 = new Commission();
        $com1->setMerchant('123456');
        $com1->setAmount(2);

        $item1 = new Item();
        $item1->setStamp('someStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr1')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setMerchant('111111')
            ->setCommission($com1)
            ->setReference('1-1')
            ->setUnits(1);

        $com2 = new Commission();
        $com2->setMerchant('123456');
        $com2->setAmount(5);
    
        $item2 = new Item();
        $item2->setStamp('someOtherStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr2')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setMerchant('222222')
            ->setReference('1-2')
            ->setUnits(2);

        $r->setItems([$item1, $item2]);

        $c = new Customer();
        $c->setEmail('customer@email.com');

        $r->setCustomer($c);

        $cb = new CallbackUrl();
        $cb->setCancel('https://somedomain.com/cancel')
            ->setSuccess('https://somedomain.com/success');

        $r->setCallbackUrls($cb);

        $redirect = new CallbackUrl();
        $redirect->setSuccess('https://someother.com/success')
            ->setCancel('https://someother.com/cancel');

        $r->setRedirectUrls($redirect);

        $this->assertEquals(true, $r->validate());
    }

    public function testShopInShopPaymentRequestFail()
    {
        $this->expectException(ValidationException::class);

        $r = new ShopInShopPaymentRequest();
        $r->setAmount(30);
        $r->setStamp('RequestStamp');
        $r->setReference('RequestReference123');
        $r->setCurrency('EUR');
        $r->setLanguage('EN');

        $item1 = new Item();
        $item1->setStamp('someStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr1')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setReference('1-1')
            ->setUnits(1);

        $com2 = new Commission();
        $com2->setMerchant('123456');
        $com2->setAmount(5);
    
        $item2 = new Item();
        $item2->setStamp('someOtherStamp')
            ->setDeliveryDate('12.12.2020')
            ->setProductCode('pr2')
            ->setVatPercentage(25)
            ->setUnitPrice(10)
            ->setMerchant('222222')
            ->setReference('1-2')
            ->setUnits(2);

        $r->setItems([$item1, $item2]);

        $c = new Customer();
        $c->setEmail('customer@email.com');

        $r->setCustomer($c);

        $cb = new CallbackUrl();
        $cb->setCancel('https://somedomain.com/cancel')
            ->setSuccess('https://somedomain.com/success');

        $r->setCallbackUrls($cb);

        $redirect = new CallbackUrl();
        $redirect->setSuccess('https://someother.com/success')
            ->setCancel('https://someother.com/cancel');

        $r->setRedirectUrls($redirect);

        $r->validate();
    }
}
