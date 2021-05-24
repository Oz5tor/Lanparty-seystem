<?php

require_once("Include/Usermodule/BecomeMember.php");
require_once("class/GetUsernameFromID.php");

if(!isset($_SESSION['UserToken']) && !isset($_SESSION['UserID'])){
    header("Location: index.php");
}else{
    if(isset($_SESSION['UserToken'])){
        if(isset($_SESSION['FullName'])){ $FullName = $_SESSION['FullName'];}
        if(isset($_SESSION['Email'])){ $Email       = $_SESSION['Email'];}
        if(isset($_SESSION['PreffereredUsername'])){ $PreffereredUsername    = $_SESSION['PreffereredUsername'];}
        if(isset($_SESSION['PictureUrl'])){$PictureUrl = $_SESSION['PictureUrl'];}else{$PictureUrl = "Images/Users/nopic.png";}
    }
    if(isset($_SESSION['UserID'])){
        $UserID = $_SESSION['UserID'];
        if($result = $db_conn->query("SELECT * FROM Users WHERE UserID = '$UserID'")){
            if($result -> num_rows){
                $row = $result->fetch_assoc();
                $NewsLetter             = $row['NewsLetter'];
                $Birthday               = $row['Birthdate'];
                $PreffereredUsername    = $row['Username'];
                $FullName               = $row['FullName'];
                $Address                = $row['Address'];
                $Zipcode                = $row['ZipCode'];
                $Clan                   = $row['ClanID'];
                $Email                  = $row['Email'];
                $Phone                  = $row['Phone'];
                $Bio                    = $row['Bio'];
                if($row['ProfileIMG'] == ''){ $PictureUrl = "Images/Users/nopic.png";} else { $PictureUrl = $row['ProfileIMG']; }
                if(isset($_FILES['IMG'])){ $IMG = $_FILES['IMG']['name']; }
                
            }
        }
    }
    if(isset($_POST['Send_form'])) // Submit form start
    {
      require_once("Include/Usermodule/FormSubmit.php");
    }// Form submit end
    ?>
    <!-- Form Start -->
<div class="row LanCMScontentbox">
    <div class="col-lg-12">
      <?php require_once("Include/MsgUser.php"); ?>
    </div>
  &nbsp;
    <form action="" method="post" enctype="multipart/form-data" >
      <div class="form-group col-lg-3">
        <label class="control-label" for="FullName">Fulde Navn:*</label>
        <input type="text" class="form-control" required placeholder="Santa Claus" id="FullName"
               value="<?php if(isset($FullName)){ echo $FullName;} ?>"  name="FullName">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Email">Email:*</label>
        <input type="email" class="form-control" required id="Email" placeholder="Workshop@santa.chrismas"
               value="<?php if(isset($Email)){ echo $Email;} ?>"  name="Email">
      </div>
        
      <div class="form-group col-lg-3">
        <label class="control-label" for="Birthday">F&oslash;dselsdag:*</label>
        <input id="Birthday" class="form-control birthday " type="text" readonly required data-target="#Birthday" placeholder="dd-mm-yyyy" data-toggle="datetimepicker"  value="<?php if(isset($Birthday)){ echo date('d-m-Y',strtotime($Birthday));} ?>"
               name="Birthday">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Username">Brugernavn:*</label>
        <input type="text" required placeholder="ImNotSanta" class="form-control" id="Username"
               value="<?php if(isset($PreffereredUsername)){echo $PreffereredUsername; } ?>"  name="Username">
      </div>

      <?php
      if(!isset($_SESSION['UserID'])){
      ?>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Password">Kodeord:*</label>
        <input type="password" required class="form-control" pattern=".{4,18}" title="4 til 18 karaktere" id="Password" placeholder="Kodeord"  name="Password">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="CPassword">Bekr&aelig;ft Kodeord:*</label>
        <input type="password" required class="form-control" pattern=".{4,18}" title="4 til 18 karaktere" id="CPassword" placeholder="Gentag Kodeord"  name="CPassword">
      </div>
      <?php
      }
      ?>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Phone">Telefon:*</label>
        <input type="text" maxlength="8" required class="form-control" id="Phone" value="<?php if(isset($Phone)){echo $Phone;} ?>"
               placeholder="feks: 11223344 eller +4511223344"  name="Phone">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Address">Adresse:*</label>
        <input type="text" placeholder="feks Norpolen 42, 6.sal tv" required class="form-control" id="Address"
               value="<?php if(isset($Address)){echo $Address;} ?>"  name="Address">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Zipcode">Postnumber:*</label>
        <input type="text" list="DBZipcodes" placeholder="1337 Awesome city" required class="form-control" id="Zipcode"
        value="<?php if(isset($Zipcode)){echo $Zipcode;} ?>"  name="Zipcode">
        <!-- List of Zipcodes in Denmark -->
        <datalist id="DBZipcodes">
        <?php
        if($result = $db_conn->query("SELECT * FROM ZipCodes")){
          while($row = $result->fetch_assoc()){
          echo '<option value=',$row["zipcode"],'>',$row["zipcode"],' ',$row["city"],'</option>';
          }
        }
        ?>
        </datalist>
        <!-- List of Zipcodes in Denmark End -->
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Clan">Klan: </label> &nbsp;<a type="button" id="ClanLink" onclick="showStuff()">Ny Klan?</a>

        <!-- if existing Clans -->
        <input type="text" name="NewClan" placeholder="Kage Banden" class="form-control" style="display: none;" id="NewClan">
        <!-- if existing Clans -->
        <!-- if existing Clans -->
        <select list="DBClans" placeholder="Hovedstadens Lanparty Forening" class="form-control" id="Clan"
        value="<?php if(isset($Clan)){echo $Clan;} ?>" name="Clan">
          <option value="0">Er ikke i nogen Clan</option>
          <?php
          if($Clanresult = $db_conn->query("SELECT * FROM Clan")){
            while($Clanrow = $Clanresult->fetch_assoc()){
          ?>
          <option <?php if(isset($Clan)){if ($Clanrow["ClanID"] == $Clan){echo "selected";}}?>  value='<?php echo $Clanrow["ClanID"]; ?>'>
            <?php echo $Clanrow["ClanName"]; ?>
          </option>
          <?php
            }
          }
          ?>
        </select>
        <!-- if existing Clans end -->
        <script type="text/javascript">
        function showStuff(){
            if(document.getElementById('NewClan').style.display != 'block'){
              document.getElementById('NewClan').style.display = 'block';
            }else{
              document.getElementById('NewClan').style.display = 'none';
            }
            // hide the lorem ipsum text
            if(document.getElementById('Clan').style.display != 'none'){
              document.getElementById('Clan').style.display = 'none';
            }else{
              document.getElementById('Clan').style.display = 'block';
            }
          if(document.getElementById('ClanLink').text != 'Ny Klan?'){
            document.getElementById('ClanLink').text = 'Ny Klan?';
          }else{
            document.getElementById('ClanLink').text = 'Klan Liste?';
          }
        }
        </script>
      </div>
      <div class="form-group form-inline col-lg-3">
          <label for="NewsLetter">Nyhedbrev:</label>
          <input type="checkbox" <?php if(isset($NewsLetter) && $NewsLetter == 1){ echo 'checked';} ?> id="NewsLetter" value="1"
                 name="NewsLetter">
      </div>
      <?php if($page != 'EditMyProfile'){ ?>
      <div class="form-group form-inline col-lg-3">
            <label for="ToS"><a title="Læs betingelser i vores Privatlivspolitk & Cookies, links til disse kan findes i bunden af siden">*Bruger Betingelser:</a></label>
            <input type="checkbox" id="ToS" value="1" required name="ToS">
      </div>
      <?php } ?>
      <div class="form-group col-lg-3">
        <label>Profil Bilede:</label>
        <div class="input-group">
          <label class="input-group-btn" for="IMG">
            <span class="btn btn-primary">
              V&aelig;lg fil
              <input class="form-control" style="display:none;" type="file" id="IMG" onchange="ReadFile(this);" name="IMG">
            </span>
          </label>
          <input type="text" id="SelectedFile" class="form-control" <?php if(isset($IMG)){echo "value='$IMG'";} ?> readonly>
        </div>  
      </div>
      <div class="form-group col-lg-3">
            <img width="100" id="picIMG" style="border:solid black 1px;" src="<?php if(isset($PictureUrl)){echo $PictureUrl;} ?>">
      </div>
      <script type="text/javascript">
        
        $(function() {
          // We can attach the `fileselect` event to all file inputs on the page
          $(document).on('change', ':file', function() {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
          });
          // We can watch for our custom `fileselect` event like this
          $(document).ready( function() {
              $(':file').on('fileselect', function(event, numFiles, label) {
                  var input = $(this).parents('.input-group').find(':text'),
                      log = numFiles > 1 ? numFiles + ' files selected' : label;
                  if( input.length ) {
                      input.val(log);
                  } else {
                      if( log ) alert(log);
                  }
              });
          });
        });
        
        function ReadFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                  $('#picIMG').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }      
      </script>
      <div class="form-group col-lg-12">
        <label class="control-label" for="Bio">Profil tekst:</label>
        <textarea id="PublicTinyMCE" class="form-control" rows="5" name="Bio" id="Bio"><?php if(isset($Bio)){echo trim($Bio);} ?></textarea>
      </div>
      <div class="form-group col-xs-12 col-sm-5 col-md-6 col-lg-3">
        <?php
        if($page == 'EditMyProfile'){
        ?>
            <input type="submit" class="btn btn-default" value="Opdater Information" name="Send_form">
        <?php
        }else {
        ?>
        <input type="submit" class="btn btn-default" value="Opret bruger" name="Send_form">
        <?php } ?>
      </div>
      <?php if($page != 'EditMyProfile'){ ?>
      <div class="form-group col-xs-12 col-sm-5 col-md-6 col-lg-3">
        <!-- href="index.php?action=LogOut" -->
        <a id="CancleUser" onclick="window.location.href = 'index.php?action=LogOut'" class="btn btn-warning">Fortryd oprettelse</a>
      </div>
      <?php
      }
      if(isset($_SESSION['UserID'])){
      ?>
      <div class="visible-lg col-lg-4">
      </div>
      <div class="col-lg-5 col-xs-12 col-sm-7 col-md-6">
        <div class="col-lg-12">
          <h4>Tilføj dine andre sociale netværker: </h4>
        </div>
        <div id="oa_social_link_container" class="form-group col-lg-12"></div>
        <script type="text/javascript">
          /* Replace #your_callback_uri# with the url to your own callback script */
          var your_callback_script = 'https://<?php echo $ROOTURL; ?>Include/oneall_hlpf/oneall_callback_handler.php';
          /* Dynamically add the user_token of the currently logged in user. */
          /* Leave the field blank in case the user has no user_token yet. */
          var user_token = '<?php echo $_SESSION['OneAllToken']; ?>';
          //var user_token = 'bf7d64a9-94d4-4f77-92d8-c64e982e682a';
          /* Embeds the buttons into the oa_social_link_container */
          var _oneall = _oneall || [];
          _oneall.push(['social_link', 'set_providers', ['facebook', 'Discord', 'Battlenet', 'Steam', 'Twitch']]);
          _oneall.push(['social_link', 'set_callback_uri', your_callback_script]);
          _oneall.push(['social_link', 'set_user_token', user_token]);
          _oneall.push(['social_link', 'do_render_ui', 'oa_social_link_container']);

        </script>
      </div>
      <?php
      }
      ?>
    </form><!-- Form end -->
    <?php 
    if(isset($_SESSION['UserID'])){
    ?>
    <div id="" class="form-group col-lg-12">
      <hr>
      <?php
        $MemberTextResult = $db_conn->query("SELECT Content From Pages WHERE PageTitle = 'Membership'");
        $MemberTextRow = $MemberTextResult->fetch_assoc();
        $tempText = str_replace("MembershipPrice", $_GLOBAL['MembershipPrice'],$MemberTextRow['Content']);
        
        echo str_replace("HalfPrice", $_GLOBAL['MembershipPrice']/2,$tempText);
      // is member?
      $year = date('Y',time());
      $ismemberresult = $db_conn->query("SELECT * FROM UserMembership WHERE UserID = '$UserID' AND Year = '$year'");
      
      if($ismemberresult->num_rows == 1){
        echo "<h4>Tak for dit medlemskab i $year!</h4>";
      }else{
      ?>
      <form method="post">
        <button name="BecomeMember" class="btn btn-info">BLiv Medlem for <?php echo $year; ?></button>
      </form>
      <?php
      }
      ?>
      </div>
  <?php 
    } // end if user loggedin
    ?>
</div> <!-- Row end -->

<?php
}
?>
