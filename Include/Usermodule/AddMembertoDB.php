<?php
  require_once("class/SendMail.php");
  $year = date('Y',time());
  $UserID = $_SESSION['UserID'];
  $db_conn->query("INSERT INTO UserMembership (UserID, Year) VALUES ('$UserID', '$year')");
  unset($_SESSION["BuyingMembership"]);

  $mailMSGResult = $db_conn->query("SELECT Message FROM MailMesseges WHERE MessegesID = '2'");
  $mailMSG = $mailMSGResult->fetch_assoc();
  $msg = $mailMSG['Message'];

  $getsuerresult = $db_conn->query("SELECT FullName, Username, Email From Users WHERE UserID = '$UserID'");
  $getsuerrow = $getsuerresult->fetch_assoc();


  $price = $_GLOBAL["MembershipPrice"];
  if(date('m',time()) >= $_GLOBAL["MembershipPriceDiscountmonth"]){
    $price = $price / 2;
  }
  $toname = $getsuerrow['FullName'];
  $nick   = $getsuerrow['Username'];
  $email = $getsuerrow['Email'];
  $msg = str_replace('$toname',$toname,$msg);
  $msg = str_replace('$nick',$nick,$msg);
  $msg = str_replace('$year',$year,$msg);
  $msg = str_replace('$price',$price,$msg);

  SendMail($email,$toname,'HLParty - Tak for dit Medlemsskab',$msg,$_GLOBAL);
?>
