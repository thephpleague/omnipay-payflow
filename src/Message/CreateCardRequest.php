<?php

namespace Omnipay\Payflow\Message;

/**
 * Payflow Create Credit Card Request
 */
class CreateCardRequest extends AuthorizeRequest
{
    protected $action = 'L';

    public function getData()
    {
        
        $this->getCard()->validate();
        $data = $this->getBaseData();
        
        $data['TENDER'] = 'C';
        
        $data['ACCT'] = $this->getCard()->getNumber();
        $data['EXPDATE'] = $this->getCard()->getExpiryDate('my');
        $data['CVV2'] = $this->getCard()->getCvv();
        $data['BILLTOFIRSTNAME'] = $this->getCard()->getFirstName();
        $data['BILLTOLASTNAME'] = $this->getCard()->getLastName();
        $data['BILLTOSTREET'] = $this->getCard()->getAddress1();
        $data['BILLTOCITY'] = $this->getCard()->getCity();
        $data['BILLTOSTATE'] = $this->getCard()->getState();
        $data['BILLTOZIP'] = $this->getCard()->getPostcode();
        $data['BILLTOCOUNTRY'] = $this->getCard()->getCountry();

        return $data;
    }
}
