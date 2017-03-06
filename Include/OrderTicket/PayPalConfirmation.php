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
      SET Tickets.TransactionCode = '" . $_SESSION['invoice_number'] . "'
      WHERE Tickets.UserID = " . $_SESSION['UserID'] . " AND Tickets.EventID = " . $eventID;
  $db_conn->query($query);

  $userresult = $db_conn->query("SELECT FullName, Username, Email From Users WHERE UserID = '$_SESSION['UserID']'")->fetch_assoc();

  $toname = $userresult['FullName'];
  $nick   = $userresult['Username'];
  $email = $userresult['Email'];
  $msg = str_replace('$toname',$toname,$msg);
  $msg = str_replace('$nick',$nick,$msg);
  $msg = str_replace('$year',$year,$msg);
  $msg = str_replace('$price',$price,$msg);

  SendMail($email,$toname,'HLParty - Tak for dit Medlemsskab',$msg,$_GLOBAL);

} elseif (isset($_SESSION['BuyingTicketMulti'])) {
  // Magic on multiple users
  unset($_SESSION['BuyingTicketMulti']);
  $query = "UPDATE Tickets
      SET Tickets.TransactionCode = " . $_SESSION['invoice_number'] . "
      WHERE Tickets.UserID = " . $_SESSION['UserID'] . " AND Tickets.EventID = " . $eventID;
  $db_conn->query($query);
}
unset($_SESSION['invoice_number']);
// Fucking magic...
