<?php
require_once 'class/PaypalConfig.php';
// use
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
// ======
$sucess = $db_conn->real_escape_string($_GET['success']);
if($sucess == false){
 echo '<p>Betaling fejled/annulert</p>';
}
else{
  if(isset($_GET['paymentId'])){ $paymentId = $db_conn->real_escape_string($_GET['paymentId']);}
  if(isset($_GET['PayerID'])){ $PayerID = $db_conn->real_escape_string($_GET['PayerID']);}
  if(isset($_GET['toke'])){ $token = $db_conn->real_escape_string($_GET['token']);}
  
  $payment = Payment::get($paymentId, $PaypalAPI);
  $execute = new PaymentExecution();
  $execute->setPayerId($PayerID);
  
  try{
    $result = $payment->execute($execute,$PaypalAPI);
    
    echo '<p>Betaling opkrævet</p><pre>';
    echo print_r($result);
    echo '</pre>';
    
  }catch(Exception $ex){
    $data = json_decode($ex->getData());
    echo $data->message;
    echo '<a href="index.php"> klik her for at komme til forsiden</a>';
    //header("location: index.php");
  }
}
?>

