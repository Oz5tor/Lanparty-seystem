<?php
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;

## =============== Defined set of values on a item ==========
/*$tempItem = array();
$tempItem['Name']     = $_SERVER['PayPal']['Name'];
$tempItem['Currency'] = 'DKK';
$tempItem['Quantity'] = '1';
$tempItem['Price']    = $_SERVER['PayPal']['Price'];
$tempItem['Desc']    = 'Plads Billet ' . $_SERVER['PayPal']['SeatNumber'] . ' til ' . $_SERVER['PayPal']['Username'];
## ======== Add item or items to cart there will be used in the function ==========
$cart = array();
$cart[] = $tempItem;*/
# == Call of the funtions looks like this and req the checkout cart and a description
#PayPalCheckOut($cart, $db_conn, 'index.php', $invoiceID);

function PayPalCheckOut($Cart, $DBCONN, $returnto ,$invoiceID){
  // get the basic paypal api config and DBconn.php
  require_once("class/PayPalConfig.php");

  $total = 0;
  // get the total price
  $tempcounter = 0;
  $items = array();
  foreach($Cart as $val){
    $total += $val['Price'] * $val['Quantity'];
    $item[$tempcounter] = new Item();
    $item[$tempcounter]->setName($val['Name'])
      ->setDescription($val['Desc'])
      ->setCurrency($val['Currency'])
      ->setQuantity($val['Quantity'])
      ->setPrice($val['Price']);
    $items[] = $item[$tempcounter];
    $tempcounter++;
  }

  $Payer = new Payer();
  $Payer->setPaymentMethod('paypal');

  // insert of items into a itemlist object
  $itemList = new ItemList();
  $itemList->setItems($items);

  $details = new Details(); // Lucky we dont use any tax or shipping for Lan Tickets
  $details->setShipping(0)
    ->setTax(0)
    ->setSubtotal($total);

  $amount = new Amount();
  $amount->setCurrency('DKK')
    ->setTotal($total) // Lucky we dont use any tax or shipping for Lan Tickets
    ->setDetails($details);


  $transaction = new Transaction();
  $transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setInvoiceNumber($invoiceID);

  $redirectUrls = new RedirectUrls();
  $redirectUrls->setReturnUrl("http://localhost/Website-2017/index.php?page=Paypalpay&success=true&returnto=$returnto")
    ->setCancelUrl("http://localhost/Website-2017/index.php?page=Paypalpay&success=false");

  $payment = new Payment();
  $payment->setIntent('sale')
    ->setPayer($Payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions([$transaction]);

  try{
    $payment->create($PaypalAPI); // opret PayPal payment URL

    $paymentID = $payment->id;
    // transaction code = $invoiceid;
    $tempUser = $_SESSION['UserID'];
    //require_once 'Include/DBconn.php';
    $DBCONN->query("INSERT INTO Transactions_PayPal
                      (UserID,TransactionCode, Completed, PaymentID, CompletedTime)
                      VALUES
                      ('$tempUser','$invoiceid','0','$paymentID','NULL')");


    //echo $payment->getApprovalLink();
    header("Location: ". $payment->getApprovalLink());
  }catch (Exception $ex){
    echo '<pre>';
    var_dump($ex);
    echo '</pre>';
  }
} // Function end

//PayPalCheckOut($cart, $db_conn, 'index.php', uniqid());
?>
