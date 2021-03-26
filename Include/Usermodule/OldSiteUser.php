<?php 
require_once("class/MailGunSendMail.php");
 if (isset(($_POST["Submit_OSU"])) && isset(($_POST["Username"])) && isset(($_POST["email"])) ) {
     $Username   = $db_conn->real_escape_string($_POST['Username']);
     $Email      = $db_conn->real_escape_string($_POST['email']);
     $VCode      = md5(($Email.time()));

     if($OSUresult = $db_conn->query("SELECT * FROM Users WHERE Username = '$Username' AND Email = '$Email' AND OSUVerLinkUsed != '1'")){
        echo "User exists";
        $OSUrow = $OSUresult->fetch_assoc();
        if ($db_conn->query("UPDATE Users SET OSUVeriLink='$VCode', OSUVerLinkUsed='0' Where Username ='$Username' && Email ='$Email' ")) {
        
            $Key        = $_GLOBAL['MailgunKey'];
            $VCode      = md5(($Email.time()));
            $HTML       = "Her der din aktiverings kode: $VCode";
            $To         = "torsoya@gmail.com";
            $From       = $_GLOBAL['SendMailFrom'];
            $Subject    = "Dette er en kode test 1..2..3..4..";

            MailGunSender($From, $To, $Subject, $HTML, $Key);

        } // Insert of Verification code + send of mail
     } // Select OSU from DB Users
 }// IF Submit_OSU Button is pressed
?>

<div class="row LanCMSequal">
    <div class="LanCMScontentbox col-lg-6">
    Beskrivelse af hvad det betyder at linke din sociale profil med din gamgle HLPart.dk bruger
    </div>
    <div class="LanCMScontentbox col-lg-6">
        <div>
            <form method="post">
                <div class="form-group col-lg-12">
                    <label class="control-lable" for="Username">Brugernavn:</label>
                    <input class="form-control" type="text" name="Username" placeholder="Purpl3Ninj4" id="Username" 
                    value="<?php if(isset(($_POST["Submit_OSU"]))){echo $_POST["Username"]; } ?>">
                </div>
                <div class="form-group col-lg-12">
                    <label  class="control-lable" id="email" for="email">Email:</label>
                    <input  class="form-control" type="email" name="email" placeholder="Lan@greenland.dk" id="email"
                    value="<?php if(isset(($_POST["Submit_OSU"]))){echo $_POST["email"]; } ?>">
                </div>
                <?php
                if (isset(($_POST["Submit_OSU"])) && isset(($_POST["Username"])) && isset(($_POST["email"])) ) {?>
                <div class="form-group col-lg-12">
                    <label  class="control-lable" id="Verify" for="Verify">Verificerings kode:</label>
                    <input  class="form-control" type="Verify" name="Verify" required placeholder="<?php echo md5(("bob@email.com".time())); ?>" id="email"
                    value="">
                </div>
                <div class="form-group col-lg-12">
                    <input  class="form-control btn btn-success" type="submit" name="LinkOSU" Value="Start Linking af min gamle Bruger" id="LinkOSU">
                </div>
                <?php }else{ ?>
                <div class="form-group col-lg-12">
                    <input  class="form-control btn btn-primary" type="submit" name="Submit_OSU" Value="Send Verifikerings kode for min gamle Bruger"id="Submit_OSU">
                </div>
                <?php } ?>
            </form>
        </div>
    </div>
</div>
