<?php
require_once("class/MailGunSendMail.php");
require_once("class/GetUsernameFromID.php");
$Mailsend = 0;
 if (isset($_POST["Submit_OSU"]) && isset($_POST["Username"]) && isset($_POST["email"]) ) {
     $Username   = $db_conn->real_escape_string($_POST['Username']);
     $Email      = $db_conn->real_escape_string($_POST['email']);
     $VCode      = md5(($Email.time()));
     $OSUresult = $db_conn->query("SELECT * FROM Users WHERE Username = '$Username' AND Email = '$Email' AND OSUVerLinkUsed <> '1'");
     #print_r($OSUresult);
     #print_r($OSUresult->fetch_assoc());
     if($OSUresult->fetch_assoc()){
        #echo "User exists";
        $OSUrow = $OSUresult->fetch_assoc();
        if ($db_conn->query("UPDATE Users SET OSUVeriLink='$VCode', OSUVerLinkUsed='0' Where Username ='$Username' && Email ='$Email'")) {
        
            $Key        = $_GLOBAL['MailgunKey'];
            $VCode      = md5(($Email.time()));
            $HTML       = "Hej $Username, Her er en aktiverings kode: $VCode, for din bruger";
            $To         = "bestyrelsen@hlpf.dk";
            $From       = $_GLOBAL['SendMailFrom'];
            $Subject    = "Dette er en kode test 1..2..3..4..";

            MailGunSender($From, $To, $Subject, $HTML, $Key);
            $_SESSION['MsgForUser'] = 'Vi har sendt dig en code som du bedet indtaste i "Verificerings kode" feltet, HUSK!! at tjekke din spam mappe';
            $Mailsend = 1;
        } // Insert of Verification code + send of mail
     } // Select OSU from DB Users
     else{
        $_SESSION['MsgForUser'] = 'Der blev ikke funde nogne bruger der matched' ;
         #echo "ingen bruger fundet der passes";
         $Mailsend = 0;
     }
 }// IF Submit_OSU Button is pressed
# ===================================================
# ===================================================
 // if Verificationcode + user + mail match
 if (isset($_POST["LinkOSU"]) && isset($_POST["Username"]) && isset($_POST["email"]) && isset($_POST["Verify"]) ) {
    $Username   = $db_conn->real_escape_string($_POST['Username']);
    $Email      = $db_conn->real_escape_string($_POST['email']);
    $Verify     = $db_conn->real_escape_string($_POST['Verify']);
    #$OSUresult = $db_conn->query("SELECT * FROM Users WHERE Username = '$Username' AND Email = '$Email' AND OSUVerLinkUsed != '1' AND OSUVeriLink = '$Verify'");
    #print_r($OSUresult);
    #OSU Code Confirmation
    $OSUresult = $db_conn->query("SELECT * FROM Users WHERE Username = '$Username' AND Email = '$Email' AND OSUVerLinkUsed != '1' AND OSUVeriLink = '$Verify'");
    if($OSUresult->fetch_assoc()){
        $_SESSION["OSUID"] = GetIDFromUsername($Username, $db_conn);
        $db_conn->query("UPDATE Users SET OSUVerLinkUsed='1' Where Username ='$Username' && Email ='$Email' && OSUVeriLink = '$Verify' ");
        #update Users DB trable for code to have been used
    }
 }
?>

<div class="row LanCMSequal">
    <div class="LanCMScontentbox col-lg-6 col-md-12 col-sm-12 col-xs-12">
    Beskrivelse af hvad det betyder at linke din sociale profil med din gamgle HLPart.dk bruger
    </div>
    <div class="LanCMScontentbox col-lg-6 col-md-12 col-sm-12 col-xs-12">
    <?php
    if (isset($_SESSION['MsgForUser']) && $Mailsend == 0) { ?>
            <div class="alert alert-dismissible alert-danger col-lg-12 col-md-8 col-sm-12 col-xs-12">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Fejl!</strong> <?= $_SESSION['MsgForUser'] ?>
            </div>
    <?php
    unset($_SESSION['MsgForUser']);
    }
    if (isset($_SESSION['MsgForUser']) && $Mailsend == 1) { ?>
        <div class="alert alert-dismissible alert-success col-lg-12 col-md-8 col-sm-12 col-xs-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Fejl!</strong> <?= $_SESSION['MsgForUser'] ?>
        </div>
    <?php
    unset($_SESSION['MsgForUser']);
    } ?>
        <div>
        <?php 
            if (isset($_SESSION["OSUID"])) {
                ?>
                <!-- ===================================== -->
                <div class="container-solid">
                <div id="oa_social_login_container"></div>
                <script type="text/javascript">
                /* Replace #your_callback_uri# with the url to your own callback script */
                var your_callback_script = 'https://<?php echo $ROOTURL; ?>Include/oneall_hlpf/oneall_callback_handler.php';
                /* Embeds the buttons into the container oa_social_login_container */
                var _oneall = _oneall || [];
                _oneall.push(['social_link', 'set_providers', ['facebook', 'Battlenet', 'Discord', 'Twitch', 'Steam']]);
                _oneall.push(['social_link', 'set_callback_uri', your_callback_script]);
                _oneall.push(['social_link', 'do_render_ui', 'oa_social_login_container']);
                </script>
                </div>
                <!-- ===================================== -->
                <?php
            }else{
                ?>
                <form method="post">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-lable" for="Username">Brugernavn:</label>
                        <input class="form-control" type="text" name="Username" placeholder="Purpl3Ninj4" required id="Username" 
                        value="<?php if(isset(($_POST["Submit_OSU"]))){echo $_POST["Username"]; } ?>">
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label  class="control-lable" id="email" for="email">Email:</label>
                        <input  class="form-control" type="email" name="email" placeholder="Lan@greenland.dk" required id="email"
                        value="<?php if(isset(($_POST["Submit_OSU"]))){echo $_POST["email"]; } ?>">
                    </div>
                    <?php
                    if ($Mailsend == 1) {?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label  class="control-lable" id="Verify" for="Verify">Verificerings kode:</label>
                        <input  class="form-control" type="Verify" name="Verify" required placeholder="<?php echo md5(("bob@email.com".time())); ?>" id="email"
                        value="">
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input  class="form-control btn btn-success" type="submit" name="LinkOSU" Value="Start Linking af min gamle Bruger" id="LinkOSU">
                    </div>
                    <?php }else{ ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input  class="form-control btn btn-primary" type="submit" name="Submit_OSU" Value="Send Verifikerings kode for min gamle Bruger"id="Submit_OSU">
                    </div>
                <?php } ?>
                </form>
                <?php
            }
        ?>
        </div>
    </div>
</div>

