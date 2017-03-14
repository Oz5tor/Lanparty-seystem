<?php
require_once("class/SendMail.php");
require_once("class/GetUsernameFromID.php");

// REMEMBER ME // REMEMBER ME
// REMEMBER ME // REMEMBER ME
// REMEMBER ME // REMEMBER ME
$eventID = 35; // REMEMBER ME
// REMEMBER ME // REMEMBER ME
// REMEMBER ME // REMEMBER ME
// REMEMBER ME // REMEMBER ME

if (!isset($_SESSION['BuyingTicketSingle']) AND !isset($_SESSION['BuyingTicketMulti'])) {
  $_SESSION['MsgForUser'] = "Fejl kode: 0x00010500";
  header("Location: index.php");
  exit();
}
if (isset($_SESSION['BuyingTicketSingle'])) {
  //
  // Magic on a single buyer
  //
  unset($_SESSION['BuyingTicketSingle']);
  $query = "UPDATE Tickets
      SET Tickets.TransactionCode = '" . $db_conn->real_escape_string($_SESSION['invoice_number']) . "'
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
  $query = "SELECT Message FROM MailMesseges WHERE MessegesID = 3";
  $mailResult = $db_conn->query($query)->fetch_assoc();
  $msg = $mailResult['Message'];
  $FullName = $userresult['FullName'];
  $username = $userresult['Username'];
  $email = $userresult['Email'];
  $msg = str_replace('$firstName', $FullName, $msg);
  $msg = str_replace('$username', $username, $msg);
  $msg = str_replace('$price', $_SESSION['Cart'][0]['Price'], $msg);
  $msg = str_replace('$antal', $_SESSION['Cart'][0]['Quantity'], $msg);

  SendMail($email, $FullName, 'Billet kvittering - HLParty', $msg, $_GLOBAL);

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
        " AND Tickets.TransactionCode IS NULL AND Tickets.EventID = " . $eventID .
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
  }
  $userresult = $db_conn->query("SELECT FullName, Username, Email From Users
      WHERE UserID = " . $_SESSION['UserID'])->fetch_assoc();
  $mailResult = $db_conn->query("SELECT Message FROM MailMesseges WHERE MessegesID = 5")->fetch_assoc();
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
  SendMail($userresult['Email'], $userresult['FullName'], 'Billet kvittering - HLParty', $msg, $_GLOBAL);
}
unset($_SESSION['invoice_number'], $_SESSION['payPalSuccess'], $_SESSION['Cart']);
// Fucking magic...
