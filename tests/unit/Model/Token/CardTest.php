<?php

use OpMerchantServices\SDK\Model\Token\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function testCardValidity()
    {
        $card = new Card();
        $card->setType('Visa');
        $card->setBin('415301');
        $card->setPartialPan('0024');
        $card->setExpireYear('2023');
        $card->setExpireMonth('11');
        $card->setCvcRequired('no');
        $card->setFunding('debit');
        $card->setCategory('unknown');
        $card->setCountryCode('FI');
        $card->setPanFingerprint('693a68deec6d6fa363c72108f8d656d4fd0b6765f5457dd1c139523f4daaafce');
        $card->setCardFingerprint('c34cdd1952deb81734c012fbb11eabc56c4d61d198f28b448327ccf13f45417f');

        $jsonData = $card->jsonSerialize();

        $expectedArray = [
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
        ];

        $this->assertEquals(true, $card->validate());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($jsonData));
    }
}
