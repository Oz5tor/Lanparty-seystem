<?php 
require_once("class/GetUsernameFromID.php");
$tabsEventID = $_GLOBAL['EventID'];

# ============= Actions start ============
if(isset($_GET['id'])){
  $GetTicketID = $_GET['id'];
}
# if Cancel Ticket button is pressed
if(isset($_GET['action']) && ($_GET['action'] == 'CancelTicket') ){
  $TicketActionCheck = $db_conn->query(" SELECT * FROM Tickets WHERE TicketID = '$GetTicketID' ");

  if($TicketActionCheckRow = $TicketActionCheck->fetch_assoc()){
    
    $TicketTransactionCode  = $TicketActionCheckRow['TransactionCode'];
    $TicketCancelDate       = $TicketActionCheckRow['RevokeDate'];

    if ($TicketTransactionCode != '') {
      # Set Revoke Date
      $rewokeTime = time();
      $db_conn->query("UPDATE Tickets SET RevokeDate = '$rewokeTime' WHERE TicketID = '$GetTicketID'");
      header("Location: index.php?page=Admin&subpage=Tickets#admin_menu");
    }else {
      # Delete Row in DB compleetly
      $db_conn->query("DELETE FROM Tickets WHERE TicketID = '$GetTicketID'");
      header("Location: index.php?page=Admin&subpage=Tickets#admin_menu");
    }

  }
}# if Cancel Ticket button is pressed END

# =========================
  # Set Ticket as Paied (Manuel Payment)
  if(isset($_GET['action']) && ($_GET['action'] == 'PayTicket') ){
    echo $PaymentCode = 'Manuel-'.uniqid();
    $db_conn->query("UPDATE Tickets SET TransactionCode = '$PaymentCode' WHERE TicketID = '$GetTicketID'");
    header("Location: index.php?page=Admin&subpage=Tickets#admin_menu");

  }
# ============= Actions End ==============

switch ($action) {
  case 'ReserveSeat':
    # code...
    include_once("Include/Admin/Tickets/ReserveSeat.php");
    break;
  
  default:
    # code...
    include_once("Include/Admin/Tickets/TicketList.php");
    break;
}
?>