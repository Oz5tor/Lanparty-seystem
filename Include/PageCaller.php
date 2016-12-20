<?php
if(! empty($_SESSION['UserToken']) ){
    include_once("Include/Usermodule/EditOrRegister.php");
}
switch($page){
    case "EditMyProfile":
        include_once("Include/Usermodule/EditOrRegister.php");
        break;
    case "Forside":
        include_once("Include/Home.php");   
        break;
}
?>