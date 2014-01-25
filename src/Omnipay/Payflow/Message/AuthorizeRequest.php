<?php

namespace Omnipay\Payflow\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Payflow Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://payflowpro.paypal.com';
    protected $testEndpoint = 'https://pilot-payflowpro.paypal.com';
    protected $action = 'A';

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getVendor()
    {
        return $this->getParameter('vendor');
    }

    public function setVendor($value)
    {
        return $this->setParameter('vendor', $value);
    }

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getComment2()
    {
        return $this->getParameter('comment2');
    }

    public function setComment2($value)
    {
        return $this->setParameter('comment2', $value);
    }

    protected function getBaseData()
    {
        $data = array();
        $data['TRXTYPE'] = $this->action;
        $data['USER'] = $this->getUsername();
        $data['PWD'] = $this->getPassword();
        $data['VENDOR'] = $this->getVendor();
        $data['PARTNER'] = $this->getPartner();

        return $data;
    }

    public function getData()
    {
        $this->validate('amount', 'card');

        if (!$this->getTransactionReference()) {
            $this->getCard()->validate();
        }

        $data = $this->getBaseData();
        $data['TENDER'] = 'C';
        $data['AMT'] = $this->getAmount();
        $data['COMMENT1'] = $this->getDescription();
        $data['COMMENT2'] = $this->getComment2();

        if (!$this->getTransactionReference()) {
            $data['ACCT'] = $this->getCard()->getNumber();
            $data['EXPDATE'] = $this->getCard()->getExpiryDate('my');
            $data['CVV2'] = $this->getCard()->getCvv();
        } else {
            $data['ORIGID'] = $this->getTransactionReference();
        }

        $data['EMAIL'] = $this->getCard()->getEmail();

        $data['BILLTOEMAIL'] = $this->getCard()->getEmail();
        $data['BILLTOFIRSTNAME'] = $this->getCard()->getBillingFirstName();
        $data['BILLTOLASTNAME'] = $this->getCard()->getBillingLastName();
        $data['BILLTOSTREET'] = $this->getCard()->getBillingAddress1();
        $data['BILLTOCITY'] = $this->getCard()->getBillingCity();
        $data['BILLTOSTATE'] = $this->getCard()->getBillingState();
        $data['BILLTOZIP'] = $this->getCard()->getBillingPostcode();
        $data['BILLTOCOUNTRY'] = $this->getCard()->getBillingCountry();

        $data['SHIPTOFIRSTNAME'] = $this->getCard()->getShippingFirstName();
        $data['SHIPTOLASTNAME'] = $this->getCard()->getShippingLastName();
        $data['SHIPTOSTREET'] = $this->getCard()->getShippingAddress1();
        $data['SHIPTOCITY'] = $this->getCard()->getShippingCity();
        $data['SHIPTOSTATE'] = $this->getCard()->getShippingState();
        $data['SHIPTOZIP'] = $this->getCard()->getShippingPostcode();
        $data['SHIPTOCOUNTRY'] = $this->getCard()->getShippingCountry();

        $data['ORDERID'] = $this->getOrderId();
        $data['CUSTREF'] = $this->getCustomerId();

        return $data;
    }

    public function sendData($data)
    {
        $data = $this->getData();
        $postBody = '';
        foreach ($data as $k => $v) {
            if (!empty($postBody)) {
                $postBody .= '&';
            }
            $postBody .= $k . '[' . strlen($v) . ']=' . $v;
        }

        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $postBody)->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
