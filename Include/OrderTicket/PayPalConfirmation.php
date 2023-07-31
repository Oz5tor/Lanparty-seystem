<?php
#require_once("class/SendMail.php");
require_once("class/MailGunSendMail.php");
require_once("class/GetUsernameFromID.php");

$event = $db_conn->query("SELECT EventID FROM Event
                          ORDER BY EventID DESC LIMIT 1")->fetch_assoc();
$eventID = $event['EventID'];

if (!isset($_SESSION['BuyingTicketSingle']) AND !isset($_SESSION['BuyingTicketMulti'])) {
  $_SESSION['MsgForUser'] = "Fejl kode: 0x00010500";
  header("Location: index.php");
  exit();
}
if (isset($_SESSION['BuyingTicketSingle'])) {
  //
  // Magic on a single buyer
  //

    $GetTicketTypeIDprice = $_SESSION['Cart'][0]['Price'];
    $tempYear = date("Y");
    $tempUserID  = $_SESSION['UserID'];
    $TempEventID = $_GLOBAL["EventID"];
    $IsUserMember = $db_conn->query("SELECT ID FROM UserMembership WHERE UserID = '$tempUserID' AND `Year` = '$tempYear'")->fetch_assoc();
    if(!empty($IsUserMember)){
        # == user is member during purches
        $TempTickType = "Medlem";
    }else{
        # == useris not member during pruches
        $TempTickType = "ikke-medlem";
    }
    $GetTicketTypeID = $db_conn->query("SELECT * FROM TicketPrices WHERE EventID = '$TempEventID' AND Price = '$GetTicketTypeIDprice' AND `Type` = '$TempTickType'")->fetch_assoc();
    $priceGroupID = $GetTicketTypeID['TicketPriceID'];
    
  unset($_SESSION['BuyingTicketSingle']);
  $query = "UPDATE Tickets
      SET Tickets.TransactionCode = '" . $db_conn->real_escape_string($_SESSION['invoice_number']) . "'
      , TicketPriceID = '$priceGroupID'
      WHERE Tickets.UserID = " . $_SESSION['UserID'] .
      " AND Tickets.EventID = " . $eventID .
      " AND Tickets.SeatNumber = " . $db_conn->real_escape_string(substr($_SESSION['Cart'][0]['Desc'], -3));
  $db_conn->query($query);

  if (isset($_SESSION['payPalSuccess']) AND !$_SESSION['payPalSuccess'] ) {
    $query = "UPDATE Tickets
        SET TIckets.RevokeDate = " . time() .
      " WHERE Tickets.UserID = " . $_SESSION['UserID'] .
        " AND Tickets.EventID = " . $eventID .
        " AND Tickets.TransactionCode = " . $db_conn->real_escape_string($_SESSION['invoice_number']);
    $db_conn->query($query);
  }

  $userresult = $db_conn->query("SELECT FullName, Username, Email From Users
      WHERE UserID = " . $_SESSION['UserID'])->fetch_assoc();
  $query = "SELECT Message FROM MailMesseges WHERE MessegesID = 2";
  $mailResult = $db_conn->query($query)->fetch_assoc();
  $msg = $mailResult['Message'];
  # ================= Send Mail prep ===========

  $FullName = $userresult['FullName'];
  $username = $userresult['Username'];
  $email = $userresult['Email'];
  $EventName = $_GLOBAL['EventName'];
  $MailSeat = substr($_SESSION['Cart'][0]['Desc'], -3);
  $invoice_number = $_SESSION["invoice_number"];
  $EventDate = $db_conn->query("SELECT * FROM `Event` WHERE EventID = '$eventID' LIMIT 1")->fetch_assoc();
  #print_r($EventDate);
  $EventDate = date('d/m/y', $EventDate['StartDate']);

  $msg = str_replace('$Username', $username, $msg);
  $msg = str_replace('$Fullname', $FullName, $msg);
  $msg = str_replace('$TicketCost', $GetTicketTypeIDprice, $msg);
  $msg = str_replace('$Seat', $MailSeat, $msg);
  $msg = str_replace('$EventName', $EventName, $msg);
  $msg = str_replace('$EventDate', $EventDate, $msg);
  $msg = str_replace('$Invoice', $invoice_number, $msg);

  echo "<hr/>".$msg."<hr/>";

  # =============================
    $Key        = $_GLOBAL['MailgunKey'];
    #$HTML       = $Body;#Insert body from Newsletter
    #$To         = "torsoya@gmail.com";#define this when calling the functions instead of here for newsletters
    $From       = $_GLOBAL['SendMailFrom'];
    $Subject    = "Bekræftelses af betaling for deltagelse ved $EventName";#Insert Subject from Newsletter
    $Sending = 1;
    MailGunSender($From, $email, $Subject, $msg, $Key);

} elseif (isset($_SESSION['BuyingTicketMulti'])) {
  //
  // Magic on multiple users
  //
  unset($_SESSION['BuyingTicketMulti']);
  // For every item in cart, set transaction code.
  for ($i=0; $i < count($_SESSION['Cart']); $i++) {
     $username = substr($_SESSION['Cart'][$i]['Desc'], 15);
     $usernameID = GetIDFromUsername($username, $db_conn);
    $seat = substr($_SESSION['Cart'][$i]['Desc'], 7, 3);
    $query = "UPDATE Tickets
        SET Tickets.TransactionCode = '" . $db_conn->real_escape_string($_SESSION['invoice_number']) . "'" .
      " WHERE Tickets.UserID = " . $usernameID .
        " AND Tickets.TransactionCode = '' AND Tickets.EventID = " . $eventID .
        " AND Tickets.SeatNumber = " . $db_conn->real_escape_string($seat);
    $db_conn->query($query);
    // If the payment was unsuccessful, set revoke date.
    if (isset($_SESSION['payPalSuccess']) AND !$_SESSION['payPalSuccess'] ) {
      $query = "UPDATE Tickets
          SET TIckets.RevokeDate = " . time() .
        " WHERE Tickets.UserID = " . $usernameID .
          " AND Tickets.EventID = " . $eventID .
          " AND Tickets.TransactionCode = " . $db_conn->real_escape_string($_SESSION['invoice_number']);
      $db_conn->query($query);
    }

    /*
    ## NEW SEND MAIL (Send mail to buyer with all tickets and single ticket copy to each friend)
    if($usernameID == $_SESSION['UserID']){
        # Send Mail with all Tickets
    }else{
        # Send mail with ticket to friend
    }

    */
  }


  # ============== Rework multiticket send mail Needed ============
  $userresult = $db_conn->query("SELECT FullName, Username, Email From Users
      WHERE UserID = " . $_SESSION['UserID'])->fetch_assoc();
  $mailResult = $db_conn->query("SELECT Message FROM MailMesseges WHERE MessegesID = 1")->fetch_assoc();
  $msg = $mailResult['Message'];
  $username = $userresult['Username'];
  $msg = str_replace('$fullName', $userresult['FullName'], $msg);
  $msg = str_replace('$username', $userresult['Username'], $msg);
  $priceTotal = 0;
  $ticketTotal = 0;
  for ($i=0; $i < count($_SESSION['Cart']); $i++) {
    $priceTotal = $priceTotal + $_SESSION['Cart'][$i]['Price'];
    $ticketTotal = $ticketTotal + $_SESSION['Cart'][$i]['Quantity'];
  }
  $msg = str_replace('$price', $priceTotal, $msg);
  $msg = str_replace('$antal', $ticketTotal, $msg);
  // Add the whole cart to the email at some point. It's not important. Just make the mail work.
  #SendMail($userresult['Email'], $userresult['FullName'], 'Billet kvittering - HLParty', $msg, $_GLOBAL);
  
  # ============== Rework multiticket send mail Needed ============


}
unset($_SESSION['invoice_number'], $_SESSION['payPalSuccess'], $_SESSION['Cart']);
// Fucking magic...