  <div class="container">
    <div class="row LanCMSequal">
      <div class="hidden-xs col-lg-5 col-md-6 col-sm-5 col-xs-12" id="LanCMSLogo" style="display: inherit;"><img class="img-responsive" src="Images/logo.png"></div>
      <div class="hidden-xs hidden-sm hidden-md col-lg-2 col-xs-12 text-center" id="LanCMScountdown">
      <?php
       if( !isset($_SESSION["UserID"]) & !isset($_SESSION["UserToken"]) ){ ?>
       <h3 class="btn btn-primary">Log Ind Med ---></h3> 
       <?php } ?>
      </div>
      <div class="col-lg-5 col-md-6 col-sm-7 col-xs-12 " id="LanCMSLogin" style="max-height:81px">
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