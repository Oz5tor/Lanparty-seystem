<?php
if(! empty($_SESSION['UserToken']) ){
    include_once("Include/Usermodule/EditOrRegister.php");
}
elseif (! empty( $page ) ) {
    // See: http://stackoverflow.com/questions/6416763/checking-if-a-variable-is-an-integer-in-php
    if (! ctype_digit( strval( $page ) )) {
        switch($page){
            case "EditMyProfile":
                include_once("Include/Usermodule/EditOrRegister.php");
                break;
            case "Forside":
                include_once("Include/Home.php");
                break;
            default:
                include_once("Include/Home.php");
                break;
        }
    } else {
        include_once("Include/Page.php");
    }
} else {
    include_once("Include/Home.php");
}
?>
