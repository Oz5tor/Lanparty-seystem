&nbsp;
  <div class="container">
    <div class="row LanCMSequal">
      <div class="hidden-xs hidden-sm col-lg-4 col-md-6 " id="LanCMSLogo"><img class="img-responsive" src="Images/logo.png"></div>
      
      <div class="hidden-xs hidden-sm col-lg-4 col-md-6 " id="LanCMSLogo">
        <?php 
        if(isset($_SESSION['UserID'])){
        ?>
        <a style="height: 100%;" class="LanCMSequal btn btn-primary" href="index.php?page=Buy">
          <div style="margin-left:auto; margin-right: auto; margin-top: auto; margin-bottom: auto;">
            <p>Husk at Købe billet Til <?php echo $_GLOBAL["EventName"] ?> </p>
            <p>Klik her For at at komme direkte til Plads valg</p>
          </div>
        </a>
        <?php
        }
        ?>
      </div>
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
                <a style="height: 100%;" class="LanCMSequal btn btn-info" href="index.php?page=Event">
                  <div style="margin-left:auto; margin-right: auto; margin-top: auto; margin-bottom: auto;">
                    <p>Husk at Logge ind og Købe billet Til <?php echo $_GLOBAL["EventName"]; ?></p>
                    <p>Event information kan findes her </p>
                  </div>
                </a>
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
  &nbsp;