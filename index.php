<?php
$startScriptTime=microtime(TRUE);
ob_start();
session_start();
//session_destroy();
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
    <?php require_once 'Include/CoreParts/head.php'; ?>
</head>
<body>
    <?php include_once("Include/TestArea/DEBUGGIN.php"); ?>
    <script src="JS/Jquery/jquery.min.js"></script>
    <!-- Facebook scocial like code prep start
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/da_DK/sdk.js#xfbml=1&version=v2.7&appId=1480239178911395";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    Facebook scocial like code prep end -->
    <!-- Slider start
    <div class="hlpf_no_margin_padding hidden-xs container-fluid">
       <img src="Images/image-slider-5.jpg" class="img-responsive center-block" >
    </div>
    Slider end -->
    <br>
    <header>
        <!-- Top start -->
        <?php require_once("Include/TopHeader.php"); ?>
        <!-- Top end -->
        <br>
        <!-- Nav start -->
        <?php require_once("Include/NavigationBar.php"); ?>
        <!-- Nav end -->
    </header>
    <br>
    <?php
    if (isset($_SESSION['MsgForUser'])) {
        include_once 'Include/MsgUser.php';
    }
    ?>
    <div class="container">
    <?php require_once("Include/PageCaller.php"); ?>
    </div>
    <?php #require_once("Include/TilesAndTournament.php"); ?>
    <!-- Sponsors start -->
    <hr>
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
