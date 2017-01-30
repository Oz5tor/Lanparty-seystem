<?php
require_once("class/PayPalConfig.php");
use PayPal\Api\Item; 
use PayPal\Api\Payer; 
use PayPal\Api\Amount; 
use PayPal\Api\Details; 
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;

$Payer = new Payer();
$Payer->setPaymentMethod('paypal');

$item = new Item();
$item->setName('Kage')
  ->setCurrency('DKK')
  ->setQuantity(1)
  ->setPrice(2);

$itemList = new ItemList();
$itemList->setItems([$item]);

$details = new Details();
$details->setShipping(0)
  ->setTax(0)
  ->setSubtotal(2);

$amount = new Amount();
$amount->setCurrency('DKK')
  ->setTotal(2)
  ->setDetails($details);

$transaction = new Transaction();
$transaction->setAmount($amount)
  ->setItemList($itemList)
  ->setDescription('Virker det ?')
  ->setInvoiceNumber(uniqid());

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("http://localhost/Website-2017/index.php?page=Paypalpay&success=true")
  ->setCancelUrl("http://localhost/Website-2017/index.php?page=Paypalpay&success=false");

$payment = new Payment();
$payment->setIntent('sale')
  ->setPayer($Payer)
  ->setRedirectUrls($redirectUrls)
  ->setTransactions([$transaction]); 

try{
  $payment->create($PaypalAPI);
}catch (Exception $ex){
  die($ex);
}
//echo '<pre>';
//echo print_r($payment);
//echo '</pre>';

header("Location: ". $payment->getApprovalLink());


?>
