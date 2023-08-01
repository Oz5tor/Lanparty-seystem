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
  echo "<p>Her er en bekræftigelse på betaling, der er sendt kopi til din mail</p>";
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
     #echo " | ";
     $usernameID = GetIDFromUsername($username, $db_conn);
     #echo " | ";
    $seat = substr($_SESSION['Cart'][$i]['Desc'], 7, 3);
    #echo " |<br/>";
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

    
    ## NEW SEND MAIL (Send mail to buyer with all tickets and single ticket copy to each friend)
    if($usernameID == $_SESSION['UserID']){ # if Buyer is same as name in cart
        # Send Mail with all Tickets to whom there pay's
        $AllSeats = '';
        $AllUsers = '';
        $TotalCost = 0;
        for ($i=0; $i < count($_SESSION['Cart']); $i++) {
          $username = substr($_SESSION['Cart'][$i]['Desc'], 15);
          #echo " | ";
          $usernameID = GetIDFromUsername($username, $db_conn);
          #echo " | ";
         $seat = substr($_SESSION['Cart'][$i]['Desc'], 7, 3);
         #echo " |<br/>";


         $userresult = $db_conn->query("SELECT FullName, Username, Email From Users
         WHERE UserID = " . $usernameID)->fetch_assoc();

         $AllSeats .= $seat.', ';
         $AllUsers .= $username.', ';
         $TotalCost += $_SESSION['Cart'][$i]['Price'];

        }
        #echo $AllSeats;
        #echo "<br/>";
        #echo $AllUsers;
        #echo "<br/>";
        #echo $TotalCost;

        $userresult = $db_conn->query("SELECT FullName, Username, Email From Users
            WHERE UserID = " . $usernameID)->fetch_assoc();
        $query = "SELECT Message FROM MailMesseges WHERE MessegesID = 3";
        $mailResult = $db_conn->query($query)->fetch_assoc();
        $msg = $mailResult['Message'];
        # ================= Send Mail prep ===========


        $Ticketinfo = $db_conn->query("SELECT * FROM Tickets WHERE SeatNumber = '$seat' AND EventID = '". $_GLOBAL['EventID'] ."'")->fetch_assoc();
        $ticketinfoPrice = $db_conn->query("SELECT * FROM TicketPrices WHERE TicketPriceID = '". $Ticketinfo['TicketPriceID'] ."' ")->fetch_assoc();

        #$FullName = $userresult['FullName'];
        #$username = $userresult['Username'];
        $email = $userresult['Email'];
        $EventName = $_GLOBAL['EventName'];
        $MailSeat = substr($_SESSION['Cart'][0]['Desc'], -3);
        $invoice_number = $_SESSION["invoice_number"];
        $EventDate = $db_conn->query("SELECT * FROM `Event` WHERE EventID = '$eventID' LIMIT 1")->fetch_assoc();
        #print_r($EventDate);
        $EventDate = date('d/m/y', $EventDate['StartDate']);

        $msg = str_replace('$Username', $AllUsers, $msg);
        $msg = str_replace('$Fullname', '', $msg);
        $msg = str_replace('$TicketCost', $TotalCost, $msg);
        $msg = str_replace('$Seat', $AllSeats, $msg);
        $msg = str_replace('$EventName', $EventName, $msg);
        $msg = str_replace('$EventDate', $EventDate, $msg);
        $msg = str_replace('$Invoice', $invoice_number, $msg);
        echo "<p>Her er en bekræftigelse på betaling, der er sendt kopi til deres mail, samt specifik plads bekræftigelse til dine venner</p>";
        echo "<hr/>".$msg."<hr/>";

        # =============================
          $Key        = $_GLOBAL['MailgunKey'];
          #$HTML       = $Body;#Insert body from Newsletter
          #$To         = "torsoya@gmail.com";#define this when calling the functions instead of here for newsletters
          $From       = $_GLOBAL['SendMailFrom'];
          $Subject    = "Bekræftelses af Billet for deltagelse ved $EventName";#Insert Subject from Newsletter
          $Sending = 1;
          MailGunSender($From, $email, $Subject, $msg, $Key);

    }else{ #Send mail with ticket to friend
        $userresult = $db_conn->query("SELECT FullName, Username, Email From Users
            WHERE UserID = " . $usernameID)->fetch_assoc();
        $query = "SELECT Message FROM MailMesseges WHERE MessegesID = 2";
        $mailResult = $db_conn->query($query)->fetch_assoc();
        $msg = $mailResult['Message'];
        # ================= Send Mail prep ===========


        $Ticketinfo = $db_conn->query("SELECT * FROM Tickets WHERE SeatNumber = '$seat' AND EventID = '". $_GLOBAL['EventID'] ."'")->fetch_assoc();
        $ticketinfoPrice = $db_conn->query("SELECT * FROM TicketPrices WHERE TicketPriceID = '". $Ticketinfo['TicketPriceID'] ."' ")->fetch_assoc();

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
        $msg = str_replace('$TicketCost', $ticketinfoPrice['Price'], $msg);
        $msg = str_replace('$Seat', $seat, $msg);
        $msg = str_replace('$EventName', $EventName, $msg);
        $msg = str_replace('$EventDate', $EventDate, $msg);
        $msg = str_replace('$Invoice', $invoice_number, $msg);

        #echo "<hr/>".$msg."<hr/>";

        # =============================
          $Key        = $_GLOBAL['MailgunKey'];
          #$HTML       = $Body;#Insert body from Newsletter
          #$To         = "torsoya@gmail.com";#define this when calling the functions instead of here for newsletters
          $From       = $_GLOBAL['SendMailFrom'];
          $Subject    = "Bekræftelses af Billet for deltagelse ved $EventName";#Insert Subject from Newsletter
          $Sending = 1;
          MailGunSender($From, $email, $Subject, $msg, $Key);


    }

  } // For loop end #line 94

}
unset($_SESSION['invoice_number'], $_SESSION['payPalSuccess'], $_SESSION['Cart']);
// Fucking magic...