<?php
session_set_cookie_params(['samesite' => 'None', 'secure' => true]);
$startScriptTime=microtime(TRUE);
ob_start();
ini_set('session.cookie_samesite', 'None');
session_start();
#$_SESSION['Admin'] = 1;
#$_SESSION['UserID'] = 736;
#session_destroy();
date_default_timezone_set ('Europe/Copenhagen');
require_once("Include/CoreParts/DBconn.php");
require_once("oneall_sdk/config.php");
require_once("Include/oneall_hlpf/oneall_calls.php");
require_once("Include/CoreParts/UrlContoller.php");
require_once("Include/CoreParts/global_settings.php");
?>
<!DOCTYPE html>
<html lang="da">
<head>
  <script src="JS/Jquery/jquery.min.js"></script>
    <?php require_once 'Include/CoreParts/head.php'; ?>
</head>
<body>
    <?php include_once("Include/TestArea/DEBUGGIN.php"); ?>
    <!-- Slider start
    <div class="LanCMSno_margin_padding hidden-xs container-fluid">
       <img src="Images/image-slider-5.jpg" class="img-responsive center-block" >
    </div>
    Slider end -->
    <div class="container-fluid" id="header">
        <!-- Top start -->
        <?php require_once("Include/TopHeader.php"); ?>
        <!-- Top end -->
    </div>
    <header id="">
        <!-- Nav start -->
        <?php require_once("Include/NavigationBar.php"); ?>
        <!-- Nav end -->
    </header>
    <?php
    if (isset($_SESSION['MsgForUser'])) {
        include_once 'Include/MsgUser.php';
    }
    ?>
    <div class="container">
    <?php require_once("Include/PageCaller.php"); ?>
    </div>
    <!-- Sponsors start -->
    <?php require_once("Include/Sponsors.php"); ?>
    <!-- Sponsors end -->
    <!-- Footer start -->
    <?php require_once("Include/Footer.php"); ?>
    <!-- Footer end -->
   <?php require_once("Include/CoreParts/htmlBottom.php"); ?>
   <?php
    $endScriptTime=microtime(TRUE);
    $totalScriptTime=$endScriptTime-$startScriptTime;
    echo "\n\r".'<!-- Load time: '.number_format($totalScriptTime, 15).' seconds -->'."\n\r";
   ?>
</body>
</html>
