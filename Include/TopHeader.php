<div class="container">
    <div class="container">
        <div class="row">
        <div class="hidden-xs col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center" id="hlpf_Logo"><img class="img-responsive" src="http://placehold.it/390x80"></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center" id="hlpf_countdown">

        </div>
        
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center" id="hlpf_Login">
            <?php 
            if($_GLOBAL['OneAllActive'] == 1){
              if(!isset($_SESSION['UserID'])){ ?>
              <!-- ===================================== -->
              <div id="oa_social_login_container"></div>
                <script type="text/javascript">
                  /* Replace #your_callback_uri# with the url to your own callback script */
                  var your_callback_script = 'http://<?php echo $ROOTURL; ?>Include/oneall_hlpf/oneall_callback_handler.php';
                  /* Embeds the buttons into the container oa_social_login_container */
                  var _oneall = _oneall || [];
                  _oneall.push(['social_login', 'set_providers', ['facebook', 'Google', 'Battlenet', 'Steam', 'Twitch']]);
                  _oneall.push(['social_login', 'set_callback_uri', your_callback_script]);
                  _oneall.push(['social_login', 'do_render_ui', 'oa_social_login_container']);
                </script>
              <!-- ===================================== -->
              <?php } ?>
            <?php 
            }else{
              require_once("Include/Usermodule/FallBackLogin.php");
            }
            ?>
        </div>
        </div>
    </div>
</div>
