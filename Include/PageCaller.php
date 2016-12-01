<?php
if(! empty($_SESSION['UserToken']) ){
    include_once("/Include/Usermodule/Register.php");
}
else{
    include_once("/Include/Home.php");   
}
?>