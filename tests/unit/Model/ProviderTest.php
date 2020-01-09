<?php


use OpMerchantServices\SDK\Model\Provider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    public function testProvider()
    {
        $p = new Provider();
        $p->setGroup('Group1');
        $p->setIcon('https://somedomain.com/icons/icon.png');
        $p->setId('someId');
        $p->setName('Provider name');
        $p->setParameters(['param1' => 1, 'param2' => 2]);
        $p->setSvg('https://somedomain.com/icons/icon.svg');
        $p->setUrl('https://somedomain.com/provider-url');

        $this->assertIsArray($p->getParameters());
        $this->assertIsString($p->getGroup());
        $this->assertIsString($p->getIcon());
        $this->assertIsString($p->getId());
        $this->assertIsString($p->getName());
        $this->assertIsString($p->getSvg());
        $this->assertIsString($p->getUrl());
    }
}