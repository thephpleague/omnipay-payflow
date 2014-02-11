<?php

namespace Omnipay\Payflow\Message;

use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'currency' => 'USD',
                'card' => $this->getValidCard(),
            )
        );
    }

    public function testEncodeData()
    {
        $data = array(
            'foo' => 'bar',
            'key' => 'value &= reference',
        );

        $expected = 'foo[3]=bar&key[18]=value &= reference';
        $this->assertSame($expected, $this->request->encodeData($data));
    }
}
