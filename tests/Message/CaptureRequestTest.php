<?php namespace Omnipay\Payflow\Message;

use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount'                   => '10.00',
                'transactionReference'     => 'ABC123',
            )
        );
    }

    public function testComment1()
    {
        // comment1 is alias for description
        $this->assertSame($this->request, $this->request->setComment1('foo'));
        $this->assertSame('foo', $this->request->getComment1());
        $this->assertSame('foo', $this->request->getDescription());
    }

    public function testComment2()
    {
        $this->assertSame($this->request, $this->request->setComment2('bar'));
        $this->assertSame('bar', $this->request->getComment2());
    }

    public function testGetData()
    {
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'description' => 'things',
                'comment2' => 'more things',
                'transactionReference' => 'ABC123',
            )
        );

        $data = $this->request->getData();

        $this->assertSame('D', $data['TRXTYPE']);
        $this->assertSame('12.00', $data['AMT']);
        $this->assertSame('ABC123', $data['ORIGID']);
        $this->assertSame('things', $data['COMMENT1']);
        $this->assertSame('more things', $data['COMMENT2']);
    }

    public function testDoesntSendEmptyComments()
    {
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'transactionReference' => 'ABC123',
            )
        );

        $data = $this->request->getData();
        $this->assertArrayNotHasKey('COMMENT1', $data);
        $this->assertArrayNotHasKey('COMMENT2', $data);
    }
}
