  <div class="container">
    <div class="row">
      <div class="hidden-xs hidden-sm col-lg-4 col-md-6 " id="LanCMSLogo"><img class="img-responsive" src="Images/logo.png"></div>
      <div class="hidden-xs hidden-sm col-lg-4 col-md-6 " id="LanCMSLogo"></div>
      <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" id="LanCMSLogin">
        <?php
          if(!isset($_SESSION['UserID'])){
            if($_GLOBAL['OneAllActive'] != 1){
              ?>
              
              <?php
              require_once("Include/Usermodule/FallbackLogin.php");
            }else{
                if(!isset($_SESSION['SocialNetwork'])){
              ?>
                
              <?php
                 #require_once("Include/Usermodule/OneAllLoginBox.php");   
                }
            }
          }else{
            require_once("Include/Usermodule/Userbox.php");
          }
        ?>
      </div>
    </div>
  </div>