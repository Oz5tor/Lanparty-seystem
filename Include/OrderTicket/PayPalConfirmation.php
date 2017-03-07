<?php
require_once("class/SendMail.php");

// REMEMBER ME // REMEMBER ME
// REMEMBER ME // REMEMBER ME
// REMEMBER ME // REMEMBER ME
$eventID = 35; // REMEMBER ME
// REMEMBER ME // REMEMBER ME
// REMEMBER ME // REMEMBER ME
// REMEMBER ME // REMEMBER ME

if (!isset($_SESSION['BuyingTicketSingle'])) {
  $_SESSION['MsgForUser'] = "Fejl kode: 0x00010500";
  header("Location: index.php");
}
if (isset($_SESSION['BuyingTicketSingle'])) {
  // Magic on a single buyer
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
    unset($_SESSION['payPalSuccess']);
  }

  $userresult = $db_conn->query("SELECT FullName, Username, Email From Users
                                WHERE UserID = " . $_SESSION['UserID'])
                                ->fetch_assoc();
  $query = "SELECT Message FROM MailMesseges WHERE MessegesID = 3";
  $mailResult = $db_conn->query($query)->fetch_assoc();
  $msg = $mailResult['Message'];
  $firstName = $userresult['FullName'];
  $username = $userresult['Username'];
  $email = $userresult['Email'];
  $msg = str_replace('$firstName', $firstName, $msg);
  $msg = str_replace('$username', $username, $msg);
  $msg = str_replace('$price', $_SESSION['Cart'][0]['Price'], $msg);
  $msg = str_replace('$antal', $_SESSION['Cart'][0]['Quantity'], $msg);

  SendMail($email, $firstName, 'Billet kvittering - HLParty', $msg, $_GLOBAL);
} elseif (isset($_SESSION['BuyingTicketMulti'])) {
  // Magic on multiple users
  unset($_SESSION['BuyingTicketMulti']);
  $query = "UPDATE Tickets
      SET Tickets.TransactionCode = " . $db_conn->real_escape_string($_SESSION['invoice_number']) . "
      WHERE Tickets.UserID = " . $_SESSION['UserID'] . " AND Tickets.EventID = " . $eventID;
  $db_conn->query($query);
}
unset($_SESSION['invoice_number'], $_SESSION['payPalSuccess'], $_SESSION['Cart']);
// Fucking magic...
