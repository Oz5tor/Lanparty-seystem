<?php
// This should be the absolute first thing.
// If logging out... LOG OUT!
if (!empty($action) AND $action == "LogOut") {
    session_destroy();
    header("Location: index.php");
    exit(); // No need for data grinding when you know where they are going.
}

if(!empty($_SESSION['UserToken'])){
    include_once("Include/Usermodule/EditOrRegister.php");
}
elseif (! empty( $page ) ) {
    // Pages
    switch($page){
        case "EditMyProfile":
            include_once("Include/Usermodule/EditOrRegister.php");
            break;
        case "Forside":
            include_once("Include/Home.php");
            break;
        case "Admin":
            if($_SESSION['Admin'] == 1) {
                include_once("Include/Admin/index.php");
            } else {
                header("Location: index.php");
            }
            break;
        case "Newsarchive":
            include_once("Include/News.php");
            break;
        case "Event":
            $SQL = $db_conn->query("SELECT EventID FROM Event WHERE EndDate >".time());
            if($SQL -> num_rows){
              include_once("Include/Event.php");
            }else {
              include_once("NoPlannedEvents.php");
            }
            break;
        case "Gallery": // not in use yet
            include_once("Include/TestArea/FBAlbumAPI.php");
            break;
        /*case "Paypal":
            include_once("class/PayPalCheckout.php");
            break;*/
        case "Paypalpay":
            include_once("class/PayPalPay.php");
            break;
        case "NewsLetter":
            include_once("ShowNewsLetter.php");
            break;
        case "Mailtest":
            include_once("class/SendMail.php");
            break;
        case "Buy":
            include_once("Include/OrderTicket/ChooseSeat.php");
            break;
        case "Competitions":
            include_once("Include/Competitions/CompetitionsList.php");
            break;
        default:
            include_once("Include/Page.php");
            break;
    }
}
?>
