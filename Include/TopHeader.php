  <div class="container">
    <div class="row LanCMSequal">
      <div class="hidden-xs col-lg-4 col-md-4 col-sm-4 col-xs-12" id="LanCMSLogo"><img class="img-responsive" src="Images/logo.png"></div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center" id="LanCMScountdown"></div>
      <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12" id="LanCMSLogin">
        <?php
          if(!isset($_SESSION['UserID'])){
            if($_GLOBAL['OneAllActive'] != 1){
              require_once("Include/Usermodule/FallbackLogin.php");
            }else{
                if(!isset($_SESSION['SocialNetwork'])){
                 require_once("Include/Usermodule/OneAllLoginBox.php");   
                }
            }
          }else{
            require_once("Include/Usermodule/Userbox.php");
          }
        ?>
      </div>
    </div>
  </div>