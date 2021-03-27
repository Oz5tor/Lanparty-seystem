<?php
require_once("class/MailGunSendMail.php");
$Mailsend = 0;
 if (isset(($_POST["Submit_OSU"])) && isset(($_POST["Username"])) && isset(($_POST["email"])) ) {
     $Username   = $db_conn->real_escape_string($_POST['Username']);
     $Email      = $db_conn->real_escape_string($_POST['email']);
     $VCode      = md5(($Email.time()));
     $OSUresult = $db_conn->query("SELECT * FROM Users WHERE Username = '$Username' AND Email = '$Email' AND OSUVerLinkUsed != '1'");
     #print_r($OSUresult->fetch_assoc());
     if($OSUresult->fetch_assoc()){
        echo "User exists";
        $OSUrow = $OSUresult->fetch_assoc();
        if ($db_conn->query("UPDATE Users SET OSUVeriLink='$VCode', OSUVerLinkUsed='0' Where Username ='$Username' && Email ='$Email' ")) {
        
            $Key        = $_GLOBAL['MailgunKey'];
            $VCode      = md5(($Email.time()));
            $HTML       = "Her der din aktiverings kode: $VCode";
            $To         = "torsoya@gmail.com";
            $From       = $_GLOBAL['SendMailFrom'];
            $Subject    = "Dette er en kode test 1..2..3..4..";

            #MailGunSender($From, $To, $Subject, $HTML, $Key);
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
        </div>
    </div>
</div>
