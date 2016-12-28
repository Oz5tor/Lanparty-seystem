<?php
if(isset($_POST['Create_user']))
{
    $RegErroMSG = array();
    
    if($_POST['FullName'] == '')  {$RegErroMSG[] .='Fulde Navn';}
    if($_POST['Email'] == '')     {$RegErroMSG[] .='Email';}
    if($_POST['Birthday'] == '')  {$RegErroMSG[] .='Fødselsdag';}
    if($_POST['Username'] == '')  {$RegErroMSG[] .='Brugernavn';}
    if($_POST['Password'] == '')  {$RegErroMSG[] .='Kodeord';}
    if($_POST['CPassword'] == '') {$RegErroMSG[] .='Bekræft kodeord';}
    if($_POST['Phone'] == '')     {$RegErroMSG[] .='Telefonnummer';}
    if($_POST['Address'] == '')   {$RegErroMSG[] .='Adresse';}
    if($_POST['Zipcode'] == '')   {$RegErroMSG[] .='Postnummer';}
    if(!isset($_POST['ToS']))     {$RegErroMSG[] .='Bekræfte betingelserne';}
    
    if($_POST['Password'] != $_POST['CPassword']){
    unset($RegErroMSG);    
    $RegErroMSG = array();
    $RegErroMSG[] .= 'Kodeord & Bekræft Kodeord passed ikke sammen';
    }else
    {
        // For sucessfull filled
        // injection prevention
        $FullName   = $db_conn->real_escape_string($_POST['FullName']);
        $Email      = $db_conn->real_escape_string($_POST['Email']);
        $Birthday   = $db_conn->real_escape_string($_POST['Birthday']);
        $Username   = $db_conn->real_escape_string($_POST['Username']);
        $Password   = $db_conn->real_escape_string($_POST['Password']);
        $CPassword  = $db_conn->real_escape_string($_POST['CPassword']);
        $Phone      = $db_conn->real_escape_string($_POST['Phone']);
        $Address    = $db_conn->real_escape_string($_POST['Address']);
        $Zipcode    = $db_conn->real_escape_string($_POST['Zipcode']);
        $ToS        = $db_conn->real_escape_string($_POST['ToS']);
        $Bio        = $db_conn->real_escape_string($_POST['Bio']);
        
        $PW = hash('sha512', $Password);
        
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
        
        if($page == 'EditMyProfile'){
            
        }else
        {
            $CreateTime = time();
            $profileURL = $_SESSION['ProfileUrl'];
            $token = $_SESSION['UserToken'];
            if($db_conn->query("INSERT INTO `Users`(Username, FullName, ZipCode, Birthdate, Created, Email, Bio, UserStatus,
                                 Address, PW, Phone, $TokenRow, $profileURLCol)
                                 VALUES 
                                 ('$Username','$FullName','$Zipcode', '$Birthday','$CreateTime','$Email', '$Bio','1',
                                  '$Address','$PW','$Phone','$token','$profileURL')"))   
            {
                // stuff
                //unset $_SESSION['UserToken'];
                header("Location: index.php?page=EditMyProfile");
            }else {echo 'opret fejled';}
            
        }
    }
}
?>
<!-- Register Start -->
<div class="row">
    <div class="col-lg-12 hlpf_newsborder">
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
                                               value="<?php if(isset($_SESSION['FullName'])){ echo $_SESSION['FullName'];} ?>"  name="FullName">
                                    </td>
                                    <td><label for="Email">Email:*</label>
                                        <input type="email" class="form-control" id="Email" placeholder="Workshop@santa.chrismas" 
                                               value="<?php if(isset($_SESSION['Email'])){ echo $_SESSION['Email'];} ?>"  name="Email">
                                    </td>
                                    <td><label for="Birthday">F&oslash;dselsdag:*</label>
                                        <input type="text" placeholder="dd.mm.YYYY" class="form-control" id="Birthday" 
                                               value="<?php if(isset($_SESSION['Birthday'])){
                                                                echo date("d.m.Y",strtotime($_SESSION['Birthday']));} ?>"
                                                name="Birthday" pattern="[0-9]{2}.[0-9]{2}.[0-9]{4}" title="dd.mm.yyyy">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="Username">Brugernavn:*</label>
                                        <input type="text" placeholder="ImNotSanta" class="form-control" id="FullName"
                                               value="<?php if(isset($_SESSION['PreffereredUsername'])){echo $_SESSION['PreffereredUsername']; } ?>"  name="Username">
                                    </td>
                                    <td>
                                        <label for="Password">Kodeord:*</label>
                                        <input type="password" class="form-control" pattern=".{4,18}" title="4 til 18 karaktere" id="Password" placeholder="Kodeord"  name="Password">
                                    </td>
                                    <td>
                                        <label for="CPassword">Bekr&aelig;ft Kodeord:*</label>
                                        <input type="password" class="form-control" pattern=".{4,18}" title="4 til 18 karaktere" id="CPassword" placeholder="Gentag Kodeord"  name="CPassword">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="Phone">Telefon:*</label>
                                        <input type="text" class="form-control" id="Phone" value=""  placeholder="feks: 11223344 eller +4511223344"  name="Phone">
                                    </td>
                                    <td>
                                        <label for="Address">Adresse:*</label>
                                        <input type="text" placeholder="feks Norpolen 42, 6.sal tv" class="form-control" id="FullName" value=""  name="Address">
                                    </td>
                                    <td>
                                        <label for="Zipcode">Postnumber:*</label>
                                        <input type="text" list="DBZipcodes" placeholder="1337 Awesome city" class="form-control" id="Zipcode" value=""  name="Zipcode">
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
                                        <textarea id="Bio" class="form-control awesomplete" rows="5" name="Bio">
                                        </textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-inline">
                                            <label for="ToS">Brugerbetinelser:*</label>
                                        <input type="checkbox" class="form-control" id="ToS" value="1"  name="ToS">
                                        </div>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td class="text-center">
                                        <input type="submit" class="btn btn-default" name="Create_user">
                                    </td>
                                </tr>
                                <?php
                                if(isset($RegErroMSG)){
                                    echo '<tr><td><ul class="alert alert-danger" role="alert"><b>Husk at udfylde:</b>';
                                    foreach($RegErroMSG as $i){
                                        echo '<li>'.$i.'</li>';
                                    }
                                    echo '</li></ul></td></tr>';
                                }unset($RegErroMSG)
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
<!-- Register end -->
