<?php
if(!isset($_SESSION['UserToken']) && !isset($_SESSION['UserID'])){
    header("Location: /Website-2017/index.php");
}else{
    if(isset($_SESSION['UserToken'])){
        if(isset($_SESSION['FullName'])){ $FullName = $_SESSION['FullName'];}
        if(isset($_SESSION['Email'])){ $Email       = $_SESSION['Email'];}
        if(isset($_SESSION['PreffereredUsername'])){ $PreffereredUsername    = $_SESSION['PreffereredUsername'];}
    }
    if(isset($_SESSION['UserID'])){
        $UserID = $_SESSION['UserID'];
        if($result = $db_conn->query("SELECT * FROM Users WHERE UserID = '$UserID'")){
            if($result -> num_rows){
                $row = $result->fetch_assoc();
                $FullName               = $row['FullName'];
                $Email                  = $row['Email'];
                $PreffereredUsername    = $row['Username'];
                $Birthday               = $row['Birthdate'];
                $Phone                  = $row['Phone'];
                $Address                = $row['Address'];
                $Zipcode                = $row['ZipCode'];
                $Bio                    = $row['Bio'];
                $NewsLetter             = $row['NewsLetter'];
            }
        }
    }
    if(isset($_POST['Send_form'])) // Submit form start
    {
        $RegErroMSG = array();
        $FormAOKAY = 0;
        if($_POST['FullName'] == '')  {$RegErroMSG[] .='Fulde Navn'; $FormAOKAY = 1;}
        if($_POST['Email'] == '')     {$RegErroMSG[] .='Email'; $FormAOKAY = 1;}
        if($_POST['Birthday'] == '')  {$RegErroMSG[] .='Fødselsdag'; $FormAOKAY = 1;}
        if($_POST['Username'] == '')  {$RegErroMSG[] .='Brugernavn'; $FormAOKAY = 1;}

        if($page != 'EditMyProfile'){
            if($_POST['Password'] == '')  {$RegErroMSG[] .='Kodeord'; $FormAOKAY = 1;}
            if($_POST['CPassword'] == '') {$RegErroMSG[] .='Bekræft kodeord'; $FormAOKAY = 1;}
            if(!isset($_POST['ToS']))     {$RegErroMSG[] .='Bekræfte betingelserne'; $FormAOKAY = 1;}
        }
        if($_POST['Phone'] == '')     {$RegErroMSG[] .='Telefonnummer'; $FormAOKAY = 1;}
        if($_POST['Address'] == '')   {$RegErroMSG[] .='Adresse'; $FormAOKAY = 1;}
        if($_POST['Zipcode'] == '')   {$RegErroMSG[] .='Postnummer'; $FormAOKAY = 1;}
        if($page != 'EditMyProfile')
        {
            if($_POST['Password'] != $_POST['CPassword']){
                $FormAOKAY = 1;
            $RegErroMSG[] .= 'Kodeord & Bekræft Kodeord passed ikke sammen';
            }
        }
        if($page != 'EditMyProfile'){
            $tempUsername = $_POST['Username'];
            if($result = $db_conn ->query("SELECT Username FROM Users Where Username = '$tempUsername'")){
                if($result -> num_rows){
                    $RegErroMSG[] .='Brugernavnet findes, beklager';
                    $FormAOKAY = 1;
                }
            }
        }
                $FullName               = $_POST['FullName'];
                $Email                  = $_POST['Email'];
                $PreffereredUsername    = $_POST['Username'];
                $Birthday               = $_POST['Birthday'];
                $Phone                  = $_POST['Phone'];
                $Address                = $_POST['Address'];
                $Zipcode                = $_POST['Zipcode'];
                $Bio                    = $_POST['Bio'];
       if($FormAOKAY == 0){
            // For sucessfull filled
            // injection prevention
            $FullName   = $db_conn->real_escape_string($_POST['FullName']);
            $Email      = $db_conn->real_escape_string($_POST['Email']);
            $Birthday   = $db_conn->real_escape_string($_POST['Birthday']);
            $Username   = $db_conn->real_escape_string($_POST['Username']);
            if($page != 'EditMyProfile'){
                $Password   = $db_conn->real_escape_string($_POST['Password']);
                $CPassword  = $db_conn->real_escape_string($_POST['CPassword']);
                $ToS        = $db_conn->real_escape_string($_POST['ToS']);
                $PW = hash('sha512', $Password);
            }
            $Phone      = $db_conn->real_escape_string($_POST['Phone']);
            $Address    = $db_conn->real_escape_string($_POST['Address']);
            $Zipcode    = $db_conn->real_escape_string($_POST['Zipcode']);
            $Bio        = $db_conn->real_escape_string($_POST['Bio']);
            if(isset($_POST['NewsLetter'])){ $NewsLetter = $_POST['NewsLetter'];} else{$NewsLetter = 0;}
            $Birthday = strtotime($Birthday);

            if(isset($_SESSION['SocialNetwork'])){
                switch($_SESSION['SocialNetwork']){
                    case 'steam':
                        #$TokenRow      = 'SteamToken';
                        $profileURLCol = 'SteamURL';
                        $profileURL = $_SESSION['ProfileUrl'];
                    break;
                    case 'facebook':
                        #$TokenRow      = 'FacebookToken';
                        $profileURLCol = 'FacebookURL';
                        $profileURL = $_SESSION['ProfileUrl'];
                    break;
                    case 'twitch':
                        #$TokenRow      = 'TwitchToken';
                        $profileURLCol = 'TwitchURL';
                        $profileURL = $_SESSION['ProfileUrl'];
                    break;
                    case 'google':
                        #$TokenRow      = 'GoogleToken';
                        $profileURLCol = 'GoogleURL';
                        $profileURL = $_SESSION['ProfileUrl'];
                    break;
                    case 'battlenet':
                        #$TokenRow      = 'BattlenetToken';
                        $profileURLCol = 'BattlenetID';
                        $profileURL = $_SESSION['BattleTag'];
                    break;
                }
            }
            if($page == 'EditMyProfile'){ // user edits own informations
                if($db_conn->query("UPDATE Users SET Username = '$Username', FullName = '$FullName', ZipCode = '$Zipcode',
                                                    Birthdate = '$Birthday', Email = '$Email', Bio = '$Bio',
                                                    Address = '$Address', Phone = '$Phone', NewsLetter = '$NewsLetter'
                                    WHERE UserID = '$UserID'")){}
                    header("Location: index.php?page=EditMyProfile");
            }
            else // user creation
            {
                $CreateTime = time();
                
                $token = $_SESSION['UserToken'];
                if($db_conn->query("INSERT INTO `Users`(Username, FullName, ZipCode, Birthdate, Created, Email, Bio, Admin,
                                     Address, PW, Phone, OneallUserToken, $profileURLCol, NewsLetter)
                                     VALUES
                                     ('$Username','$FullName','$Zipcode', '$Birthday','$CreateTime','$Email', '$Bio','0',
                                      '$Address','$PW','$Phone','$token','$profileURL', '$NewsLetter')"))
                {

                    if($result = $db_conn ->query("Select Users.UserID, Users.Admin From Users Where Users.Username = '$Username'")){
                      $row = $result->fetch_assoc();
                      $tempUserID = $row['UserID'];
                      // unset SESSION there was sat by the Scocial login in Oneall_callback_handler.php since the will not be used any more after cration of the user.
                      if(isset($_SESSION['SocialNetwork'])){unset($_SESSION['SocialNetwork']);}
                      if(isset($_SESSION['UserToken'])){unset($_SESSION['UserToken']);}
                      if(isset($_SESSION['ProfileUrl'])){unset($_SESSION['ProfileUrl']);}
                      if(isset($_SESSION['FullName'])){unset($_SESSION['FullName']);}
                      if(isset($_SESSION['Email'])){unset($_SESSION['Email']);}
                      if(isset($_SESSION['PictureUrl'])){unset($_SESSION['PictureUrl']);}
                      if(isset($_SESSION['BattleTag'])){unset($_SESSION['BattleTag']);}
                      if(isset($_SESSION['PreffereredUsername'])){unset($_SESSION['PreffereredUsername']);}

                      echo $_SESSION['UserID'] = $tempUserID;
                      echo $_SESSION['Admin'] = $row['Admin'];
                      echo $_SESSION['OneAllToken'] = $token;
                      $LastLogin = time();
                      if($db_conn->query("UPDATE Users SET LastLogin = '$LastLogin' WHERE UserID = '$tempUserID'")){ echo 'Senest login opdatert';}else{echo 'Senest login Ikke opdatert';}
                      header("Location: index.php?page=EditMyProfile");
                    }else{echo 'find ny bruger fejled';}
                }else {echo 'opret fejled';}
            }
        } // if formOKAY end
    }// Form submit end
    ?>
    <!-- Form Start -->
<div class="row hlpf_contentbox">
    <div class="col-lg-12">
      <img class="img-responsive" src="Images/image-slider-5.jpg">
    </div>
  &nbsp;
    <form action="" method="post">
      <div class="form-group col-lg-3">
        <label class="control-label" for="FullName">Fulde Navn:*</label>
        <input type="text" class="form-control" placeholder="Santa Claus" id="FullName" 
               value="<?php if(isset($FullName)){ echo $FullName;} ?>"  name="FullName">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Email">Email:*</label>
        <input type="email" class="form-control" id="Email" placeholder="Workshop@santa.chrismas" 
               value="<?php if(isset($Email)){ echo $Email;} ?>"  name="Email">
      </div>
      
      <div class="form-group col-lg-3">
        <label class="control-label" for="Birthday">F&oslash;dselsdag:*</label>
        <input type="date" class="form-control" id="Birthday" value="<?php if(isset($Birthday)){ echo date("d.m.Y",$Birthday);} ?>" 
               name="Birthday" title="dd.mm.yyyy">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Username">Brugernavn:*</label>
        <input type="text" placeholder="ImNotSanta" class="form-control" id="Username" 
               value="<?php if(isset($PreffereredUsername)){echo $PreffereredUsername; } ?>"  name="Username">
      </div>
      
      <div class="form-group col-lg-3">
        <label class="control-label" for="Password">Kodeord:*</label>
        <input type="password" class="form-control" pattern=".{4,18}" title="4 til 18 karaktere" id="Password" placeholder="Kodeord"  name="Password">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="CPassword">Bekr&aelig;ft Kodeord:*</label>
        <input type="password" class="form-control" pattern=".{4,18}" title="4 til 18 karaktere" id="CPassword" placeholder="Gentag Kodeord"  name="CPassword">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Phone">Telefon:*</label>
        <input type="text" class="form-control" id="Phone" value="<?php if(isset($Phone)){echo $Phone;} ?>" 
               placeholder="feks: 11223344 eller +4511223344"  name="Phone">
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="Address">Adresse:*</label>
        <input type="text" placeholder="feks Norpolen 42, 6.sal tv" class="form-control" id="Address" 
               value="<?php if(isset($Address)){echo $Address;} ?>"  name="Address">
      </div>

      <div class="form-group col-lg-3">
        <label class="control-label" for="Zipcode">Postnumber:*</label>
        <input type="text" list="DBZipcodes" placeholder="1337 Awesome city" class="form-control" id="Zipcode" 
        value="<?php if(isset($Zipcode)){echo $Zipcode;} ?>"  name="Zipcode">
        <!-- List of Zipcodes in Denmark -->
        <datalist id="DBZipcodes">
        <?php
        if($result = $db_conn->query("SELECT * FROM ZipCodes")){
          while($row = $result->fetch_assoc()){
          echo '<option value=',$row["Zipcode"],'>',$row["Zipcode"],' ',$row["City"],'</option>';
          }
        }
        ?>
        </datalist>
        <!-- List of Zipcodes in Denmark End -->
      </div>
      <div class="form-group form-inline col-lg-3">
          <?php if($page != 'EditMyProfile'){ ?>
            <label for="ToS">*Brugerbetinelser: </label>
            <input type="checkbox" id="ToS" value="1"  name="ToS">
          <?php } ?>
      </div>
      <div class="form-group form-inline col-lg-3">
          <label for="NewsLetter">Nyhedbrev:</label>
          <input type="checkbox" <?php if(isset($NewsLetter) && $NewsLetter == 1){ echo 'checked';} ?> id="NewsLetter" value="1" 
                 name="NewsLetter">
      </div>
      
      <div class="form-group col-lg-12">
        <label class="control-label" for="Bio">Profil tekst:</label>
        <textarea id="PublicTinyMCE" class="form-control" rows="5" name="Bio" id="Bio">
        <?php if(isset($Bio)){echo $Bio;} ?>
        </textarea>
      </div>
      <?php
      if(isset($_SESSION['UserID'])){   
      ?>
        <div id="oa_social_link_container" class="form-group col-lg-12"></div>
        <script type="text/javascript"> 
          /* Replace #your_callback_uri# with the url to your own callback script */
          var your_callback_script = 'http://<?php echo $ROOTURL; ?>Include/oneall_hlpf/oneall_callback_handler.php'; 
          /* Dynamically add the user_token of the currently logged in user. */
          /* Leave the field blank in case the user has no user_token yet. */
          var user_token = '<?php echo $_SESSION['OneAllToken']; ?>';
          
          /* Embeds the buttons into the oa_social_link_container */
          var _oneall = _oneall || [];
          _oneall.push(['social_link', 'set_providers', ['facebook', 'Google', 'Battlenet', 'Steam', 'Twitch']]);
          _oneall.push(['social_link', 'set_callback_uri', your_callback_script]);
          _oneall.push(['social_link', 'set_user_token', user_token]);
          _oneall.push(['social_link', 'do_render_ui', 'oa_social_link_container']);

        </script>
      <?php 
      }
      ?>
      <div class="form-group col-lg-12">
        <input type="submit" value="Send" class="btn btn-default" name="Send_form">
      </div>
      <?php
      if(isset($RegErroMSG)){
      echo '<tr><td><ul class="alert alert-danger" role="alert"><b>Feltkravene er ikke opfyldt:</b>';
      foreach($RegErroMSG as $i){
      echo '<li>'.$i.'</li>';
      }
      echo '</li></ul></td></tr>';
      }
      unset($RegErroMSG);
      ?>
    </form><!-- Form end -->
</div> <!-- Row end -->
    
<?php
}
?>
