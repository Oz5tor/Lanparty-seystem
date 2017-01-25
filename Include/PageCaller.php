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
            include_once("Include/Newsarchive/News.php");
            break;
        case "Event":
            include_once("Include/Event/Event.php");
            break;
        /*case "Gallery": // not in use yet
            include_once("Include/FBAlbumAPI.php");
            break;*/
        case "NewsLetter":
            include_once("ShowNewsLetter.php");
            break;
        default:
            include_once("Include/Page.php");
            break;
    }
    // Actions
    switch($action){
        case "LogOut":
            session_destroy();
            header("Location: index.php");
        break;
    }
}
?>
