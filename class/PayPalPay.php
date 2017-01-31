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
  // extra check start
  $erro = false;
  if(isset($_GET['paymentId'])){ $paymentId = $db_conn->real_escape_string($_GET['paymentId']);} else{ $erro = true;}
  
  if(isset($_GET['PayerID'])){ $PayerID = $db_conn->real_escape_string($_GET['PayerID']);}
  else{ $erro = true;}
  
  if(isset($_GET['token'])){ $token = $db_conn->real_escape_string($_GET['token']);}
  else{ $erro = true;}
  
  
  if($erro === false){// extra check end
    $payment = Payment::get($paymentId, $PaypalAPI);
    $execute = new PaymentExecution();
    $execute->setPayerId($PayerID);

    try{
      $result = $payment->execute($execute,$PaypalAPI);

      $completedTime = time();
      $paymenySessionID = $result->id;


      $db_conn->query("UPDATE Transactions_PayPal 
                        SET Completed = '1', CompletedTime = '$completedTime'
                        WHERE PaymentID = '$paymenySessionID'");


      echo '<p>Betaling Gemmenført, du vil blive sendt til forsiden om 5 sekunder</p>';
      echo '<a href="index.php"> klik her for at komme til forsiden</a>';
      echo '<pre>';
      echo print_r($result);
      echo '</pre>';
      echo '';
      $retunto = $_GET['returnto'];
    echo "
      <script type='text/javascript'> setTimeout(
        function() {
            window.location = '$retunto';
        }, 5000);
      </script>
      ";
    }catch(Exception $ex){
      $data = json_decode($ex->getData());
      echo $data->message;
      echo '<a href="index.php"> klik her for at komme til forsiden</a>';
      
    }
  }
}
?>

