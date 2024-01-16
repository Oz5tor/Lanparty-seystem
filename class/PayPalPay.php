<div class="container">
<div class="col-lg-12 LanCMSflex LanCMScontentbox">
<?php
require_once 'class/PaypalConfig.php';
// use
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
// ======
$sucess = $db_conn->real_escape_string($_GET['success']);
if($sucess == 'false'){ // if false
 echo '<p>Betaling fejled/annuleret</p>';
  if(isset($_SESSION["BuyingMembership"])){
    unset($_SESSION["BuyingMembership"]);
    unset($_SESSION['invoice_number']);
  }
  if(isset($_SESSION["Cart"])){ # IF purches of ticket Cancled
    $CancledSeat = $_SESSION["Cart"][0]["Desc"];
    $CancledSeat = explode("Sæde #",$CancledSeat);
    $DropSeatReserved = $CancledSeat['1'];
    $eventID = $_GLOBAL["EventID"];
    $db_conn->query("DELETE FROM Tickets WHERE EventID = '$eventID' && SeatNumber = '$DropSeatReserved'");
    unset($_SESSION['Cart']);
    unset($_SESSION['BuyingTicketSingle']);
    unset($_SESSION['invoice_number']);

  }
  /*echo "
      <script type='text/javascript'> setTimeout(
        function() {
            window.location = 'index.php';
        }, 5000);
      </script>
      ";*/
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
      if(isset($_SESSION["BuyingMembership"]) && $_SESSION["BuyingMembership"] == 1){
        require_once("Include/Usermodule/AddMembertoDB.php");
      }

      if(isset($_SESSION["BuyingTicketSingle"]) && $_SESSION["BuyingTicketSingle"] == 1 OR
         isset($_SESSION["BuyingTicketMulti"]) && $_SESSION["BuyingTicketMulti"] == 1){
        $_SESSION['payPalSuccess'] = $sucess;
        require_once("Include/OrderTicket/PayPalConfirmation.php");
        if (isset($_SESSION['UsedCodes'])) {
          foreach ($_SESSION['UsedCodes'] as $key => $value) {
            $query = "SELECT * FROM discountcodes WHERE Code = '$value' limit 1";
            $CodeFetch = $db_conn->query($query)->fetch_assoc();
            $NewUsed = $CodeFetch['Used'] +1; 
            if ($db_conn->query("UPDATE `discountcodes` SET `Used` = '$NewUsed' WHERE `Code` ='$value'")) {
              #echo "<br>Bøh";
            }
          }
        }
        unset($_SESSION['Cart']);
        unset($_SESSION['invoice_number']);
        unset($_SESSION['payPalSuccess']);
        unset($_SESSION['avgDiscount']);
        unset($_SESSION['UsedCodes']);
      }
       #echo '<div class="LanCMScontentborder">';
       echo '<p>Betaling Gemmenført, du vil blive sendt til forsiden om 5 sekunder...</p>';
       echo '<p><a href="index.php">Klik her for at komme til forsiden</a></p>';
       #echo '<pre>';
       #echo print_r($result);
       #echo '</pre>';
       #echo '</div>';
      $retunto = $_GET['returnto'];
    /*echo "
      <script type='text/javascript'> setTimeout(
        function() {
            window.location = '$retunto';
        }, 10000);
      </script>
      ";*/
    }catch(Exception $ex){
      $data = json_decode($ex->getData());
      echo $data->message;
      echo '<a href="index.php">Klik her for at komme tilbage til hvor du var</a>';

    }
  }
}
?>
  </div>
</div>