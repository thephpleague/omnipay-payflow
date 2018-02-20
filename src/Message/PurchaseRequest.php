<?php

namespace Omnipay\Payflow\Message;

/**
 * Payflow Purchase Request
 *
 * ### Example
 *
 * <code>
 * // Create a gateway for the Payflow pro Gateway
 * // (routes to GatewayFactory::create)
 * $gateway = Omnipay::create('Payflow_Pro');
 *
 * // Initialise the gateway
 * $gateway->initialize(array(
 *     'username'       => $myusername,
 *     'password'       => $mypassword,
 *     'vendor'         => $mymerchantid,
 *     'partner'        => $PayPalPartner,
 *     'testMode'       => true, // Or false for live transactions.
 * ));
 *
 * // Next, there are two ways to conduct a sale with Payflow:
 * // 1. a reference sale in which an authorization transaction reference is passed
 * // 2. a sale in which card data is directly passed
 * //
 * // #1 should be used any time multiple charges must be committed against an authorization
 * // either because parts of an order shipped at different times or because authorization is
 * // being used to "tokenize" a card and store it on the gateway (a Paypal prescribed process).
 * // Capture can only be called once against an authorization but sale does not have this limitation.
 *
 * // @see developer.paypal.com/docs/classic/payflow/integration-guide/#submitting-reference-transactions---tokenization
 *
 * // 1. Reference (tokenized card) Sale example:
 * // $reference_id can be the transaction reference from a previous authorization, credit, capture, sale, voice auth,
 * // or void.
 * $transaction = $gateway->purchase(array(
 *     'amount'        => '10.00',
 *     'cardReference' => $reference_id,
 * ));
 *
 * // 2. Sale (with card data) example:
 * // Create a credit card object
 * // This card can be used for testing.
 * $card = new CreditCard(array(
 *             'firstName'    => 'Example',
 *             'lastName'     => 'Customer',
 *             'number'       => '4111111111111111',
 *             'expiryMonth'  => '01',
 *             'expiryYear'   => '2020',
 *             'cvv'          => '123',
 * ));
 *
 * // Do a purchase transaction on the gateway
 * $transaction = $gateway->purchase(array(
 *     'amount'                   => '10.00',
 *     'currency'                 => 'AUD',
 *     'card'                     => $card,
 * ));
 * $response = $transaction->send();
 * if ($response->isSuccessful()) {
 *     echo "Purchase transaction was successful!\n";
 *     $sale_id = $response->getTransactionReference();
 *     echo "Transaction reference = " . $sale_id . "\n";
 * }
 * </code>
 */
class PurchaseRequest extends AuthorizeRequest
{
    protected $action = 'S';
}
