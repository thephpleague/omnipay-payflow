<?php

namespace Omnipay\Payflow\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response($this->getMockRequest(), '');
    }

    public function testDecodeData()
    {
        $response = new Response($this->getMockRequest(), 'x=y');
        $data = 'BILLTOFIRSTNAME=Adrian&BILLTOLASTNAME[6]=&= Foo&TEST=Hi';

        $expected = array(
            'BILLTOFIRSTNAME' => 'Adrian',
            'BILLTOLASTNAME' => '&= Foo',
            'TEST' => 'Hi',
        );

        $this->assertSame($expected, $response->decodeData($data));
    }

    public function testDecodeDataSimple()
    {
        $response = new Response($this->getMockRequest(), 'x=y');
        $data = 'foo=bar';
        $expected = array('foo' => 'bar');
        $this->assertSame($expected, $response->decodeData($data));
    }

    public function testDecodeDataEmpty()
    {
        $response = new Response($this->getMockRequest(), 'x=y');
        $data = '';
        $expected = array();
        $this->assertSame($expected, $response->decodeData($data));
    }

    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('A10A6AE7042E', $response->getTransactionReference());
        $this->assertEquals('Approved', $response->getMessage());
        $this->assertEquals(0, $response->getCode());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('User authentication failed', $response->getMessage());
        $this->assertEquals(1, $response->getCode());
    }

    public function testPurchaseDecline()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseDecline.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('A10AA63244D1', $response->getTransactionReference());
        $this->assertEquals('Declined', $response->getMessage());
        $this->assertEquals(12, $response->getCode());
    }

    public function testCreateCard()
    {
        $httpResponse = $this->getMockHttpResponse('CreateCardSuccess.txt');

        /* @var \Omnipay\Payflow\Message\CreateCardRequest $request */
        $request = m::mock('\Omnipay\Payflow\Message\CreateCardRequest');

        $response = new Response($request, $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('A10AA633AB30', $response->getCardReference());
        $this->assertEquals('Approved', $response->getMessage());
        $this->assertEquals(0, $response->getCode());
    }
}
