<?php
if(! empty($_SESSION['UserToken']) ){
    include_once("Include/Usermodule/EditOrRegister.php");
}
elseif (! empty( $page ) ) {
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
        default:
            include_once("Include/Page.php");
            break;
    }
}
?>
