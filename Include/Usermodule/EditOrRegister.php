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
                        $TokenRow      = 'SteamToken';
                        $profileURLCol = 'SteamURL';
                    break;
                    case 'facebook':
                        $TokenRow      = 'FacebookToken';
                        $profileURLCol = 'FacebookURL';
                    break;
                    case 'twitch':
                        $TokenRow      = 'TwitchToken';
                        $profileURLCol = 'TwitchURL';
                    break;
                    case 'google':
                        $TokenRow      = 'GoogleToken';
                        $profileURLCol = 'GoogleURL';
                    break;
                    case 'battlenet':
                        $TokenRow      = 'BattlenetToken';
                        $profileURLCol = 'BattlenetID';
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
                $profileURL = $_SESSION['ProfileUrl'];
                $token = $_SESSION['UserToken'];
                if($db_conn->query("INSERT INTO `Users`(Username, FullName, ZipCode, Birthdate, Created, Email, Bio, Admin,
                                     Address, PW, Phone, $TokenRow, $profileURLCol, NewsLetter)
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
    <div class="row">
        <div class="col-lg-12 hlpf_contentbox">
            <div class="row">
                <div class="col-lg-12 hlpf_large_news_box">
                    <img class="img-responsive" src="Images/image-slider-5.jpg">
                    <hr/>
                    <div class="hlpf_flex">
                        <div class="table-responsive">
                            <table class="table">
                                <form action="" method="post">
                                    <tr>
                                        <td>
                                            <label for="FullName">Fulde Navn:*</label>
                                            <input type="text" class="form-control" placeholder="Santa Claus" id="FullName"
                                                   value="<?php if(isset($FullName)){ echo $FullName;} ?>"  name="FullName">
                                        </td>
                                        <td><label for="Email">Email:*</label>
                                            <input type="email" class="form-control" id="Email" placeholder="Workshop@santa.chrismas"
                                                   value="<?php if(isset($Email)){ echo $Email;} ?>"  name="Email">
                                        </td>
                                        <td><label for="Birthday">F&oslash;dselsdag:*</label>
                                            <input type="date" class="form-control" id="Birthday"
                                                   value="<?php if(isset($Birthday)){ echo date("d.m.Y",$Birthday);} ?>"
                                                    name="Birthday" title="dd.mm.yyyy">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="Username">Brugernavn:*</label>
                                            <input type="text" placeholder="ImNotSanta" class="form-control" id="FullName"
                                                   value="<?php if(isset($PreffereredUsername)){echo $PreffereredUsername; } ?>"  name="Username">
                                        </td>
                                        <?php
                                        if($page == 'EditMyProfile'){
                                        ?>
                                        <td>
                                            &nbsp;
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                        <?php
                                        }else{
                                        ?>
                                        <td>
                                            <label for="Password">Kodeord:*</label>
                                            <input type="password" class="form-control" pattern=".{4,18}" title="4 til 18 karaktere" id="Password" placeholder="Kodeord"  name="Password">
                                        </td>
                                        <td>
                                            <label for="CPassword">Bekr&aelig;ft Kodeord:*</label>
                                            <input type="password" class="form-control" pattern=".{4,18}" title="4 til 18 karaktere" id="CPassword" placeholder="Gentag Kodeord"  name="CPassword">
                                        </td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="Phone">Telefon:*</label>
                                            <input type="text" class="form-control" id="Phone" value="<?php if(isset($Phone)){echo $Phone;} ?>"  placeholder="feks: 11223344 eller +4511223344"  name="Phone">
                                        </td>
                                        <td>
                                            <label for="Address">Adresse:*</label>
                                            <input type="text" placeholder="feks Norpolen 42, 6.sal tv" class="form-control" id="FullName" value="<?php if(isset($Address)){echo $Address;} ?>"  name="Address">
                                        </td>
                                        <td>
                                            <label for="Zipcode">Postnumber:*</label>
                                            <input type="text" list="DBZipcodes" placeholder="1337 Awesome city" class="form-control" id="Zipcode" value="<?php if(isset($Zipcode)){echo $Zipcode;} ?>"  name="Zipcode">
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
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <label for="Bio">Profil tekst:</label>
                                            <textarea id="PublicTinyMCE" class="form-control awesomplete" rows="5" name="Bio"><?php if(isset($Bio)){echo $Bio;} ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="form-inline">
                                  <?php if($page != 'EditMyProfile'){ ?>
                                          <label for="ToS">*Brugerbetinelser:</label>
                                          <input type="checkbox" class="form-control" id="ToS" value="1"  name="ToS">&nbsp; |&nbsp;
                                  <?php } ?>
                                          <label for="ToS">Nyhedbrev:</label>
                                          <input type="checkbox" <?php if(isset($NewsLetter) && $NewsLetter == 1){ echo 'checked';} ?> class="form-control" id="NewsLetter" value="1"  name="NewsLetter">
                                          </div>
                                          </td>
                                        <td>&nbsp;</td>
                                        <td class="text-center">
                                            <input type="submit" value="Send" class="btn btn-default" name="Send_form">
                                        </td>
                                    </tr>
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
                                </form>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
        </div>
    </div>
    <!-- Form end -->
<?php
}
?>
