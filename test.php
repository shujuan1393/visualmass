<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('braintree/lib/Braintree.php');

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('t6f3x7thfrp85fxr');
Braintree_Configuration::publicKey('zwffr27gfdksxmxz');
Braintree_Configuration::privateKey('f4d205166ddd37027a37a5fed3cdbba5');

//echo($clientToken = Braintree_ClientToken::generate());

$nonceFromTheClient = $_POST["payment_method_nonce"];

//echo $nonceFromTheClient;

$result = Braintree_Transaction::sale([
  'amount' => '10.00',
  'paymentMethodNonce' => 'fake-valid-nonce',
  'options' => [
    'submitForSettlement' => True
  ]
]);

$result->success;
$transaction = $result->transaction;
$transaction->status;
//echo $result;

$orderid = "ON-".rand();
echo $orderid;