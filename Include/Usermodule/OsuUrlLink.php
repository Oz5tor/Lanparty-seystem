<?php
require_once("class/GetUsernameFromID.php");

# ================== Check if Username + Vcode match user in DB ====================
# Set $_SESSION['OSUID'] to reuser function in oneall_callback_handler.php

if (isset($_GET["username"]) && isset($_GET["code"]) ) {
    ## Clean and secure URL variable
    $Username   = $db_conn->real_escape_string($_GET['username']);
    #$Email     = $db_conn->real_escape_string($_POST['email']); // used in main OldSiteUser.php but not here
    $Verify     = $db_conn->real_escape_string($_GET['code']);
    
    $OSUresult = $db_conn->query("SELECT * FROM Users WHERE Username = '$Username' AND OSUVerLinkUsed != '1' AND OSUVeriLink = '$Verify'");
    #print_r($OSUresult);
    if($OSUresult->fetch_assoc()){
        $_SESSION["OSUID"] = GetIDFromUsername($Username, $db_conn);
        $db_conn->query("UPDATE Users SET OSUVerLinkUsed = '1' Where Username ='$Username' && OSUVeriLink = '$Verify' ");
        #update Users DB trable for code to have been used
        ?>
        <div class="row LanCMSequal">
            <div class="LanCMScontentbox col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <!--  style="margin: 0; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);"  -->
                <h4>Nu skal du vælge en af de Sociale Platforme her til Højer -></h4>
            </div>
            <div class="LanCMScontentbox col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <br>
                <!-- ===================================== -->
                <div class="container-solid" >
                <div id="oa_social_login_container"></div>
                <script type="text/javascript">
                /* Replace #your_callback_uri# with the url to your own callback script */
                var your_callback_script = 'https://<?php echo $ROOTURL; ?>Include/oneall_hlpf/oneall_callback_handler.php';
                /* Embeds the buttons into the container oa_social_login_container */
                var _oneall = _oneall || [];
                _oneall.push(['social_link', 'set_providers', ['Battlenet', 'Discord', 'Twitch', 'Steam']]);
                _oneall.push(['social_link', 'set_callback_uri', your_callback_script]);
                _oneall.push(['social_link', 'do_render_ui', 'oa_social_login_container']);
                </script>
                </div>
                <!-- ===================================== -->
            </div>
        </div>
        <?php
    }else{
        ?>
        <div class="row LanCMSequal">
            <div class="LanCMScontentbox col-lg-12 col-md-12 col-sm-12 col-xs-12">
            burger allrede re-aktiverert, du vil blive sendt til forsiden om 5 Secunder.
            <?php
            echo "
            <script type='text/javascript'> setTimeout(
                function() {
                    window.location = 'index.php';
                }, 5000);
            </script>
            ";
            ?>
            </div>
        </div>
        <?php 
    }
}

?>