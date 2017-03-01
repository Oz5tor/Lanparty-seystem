<?php 
if(isset($_POST["BecomeMember"])){
require_once("class/PayPalCheckout.php");
## =============== Defined set of values on a item ==========

$price = $_GLOBAL["MembershipPrice"];
if(date('m',time()) >= $_GLOBAL["MembershipPriceDiscountmonth"]){
  $price = $price / 2;
}
$year = date('Y',time());
$month = date('m',time());
$Membership = array();
$Membership['Name']     = "Medlemskab for $year";
$Membership['Currency'] = 'DKK';
$Membership['Quantity'] = '1';
$Membership['Price']    = $price;
$Membership['Desc']    = "Medlemskab for $year";
## ======== Add item or items to cart there will be used in the function ==========
$_SESSION["BuyingMembership"] = 1;
$cart = array();
$cart[] = $Membership;
PayPalCheckOut($cart, $db_conn, 'index.php?page=EditMyProfile', uniqid());
}
?>