<?php
use PayPal\Api\Item; 
use PayPal\Api\Payer; 
use PayPal\Api\Amount; 
use PayPal\Api\Details; 
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;

$tempItem = array();
$tempItem2 = array();
$cart = array();

$tempItem['Name']     = 'Billet';
$tempItem['Currency'] = 'DKK';
$tempItem['Quantity'] = '1';
$tempItem['Price']    = '5';

$tempItem2['Name']     = 'Morgenmad';
$tempItem2['Currency'] = 'DKK';
$tempItem2['Quantity'] = '1';
$tempItem2['Price']    = '2';

$cart[] = $tempItem;
$cart[] = $tempItem2;

echo '<pre>';
echo print_r($cart);
echo '</pre>';

function PayPalCheckOut($Cart,$description){
  
  require_once("class/PayPalConfig.php");
  
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
    echo $payment->getApprovalLink();
    //header("Location: ". $payment->getApprovalLink());
  }catch (Exception $ex){
    die($ex);
  }
} // Function end



PayPalCheckOut($cart,'viker dette med flere dynamiske items');
?>
