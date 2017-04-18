<?php
if(isset($_POST["Login"])){
  if($_POST["Login"] != 'Logind'){
    require_once("class/SendMail.php");
    $username = $_POST['Username'];
    $email    = $_POST['email'];
    $zipcode  = $_POST['Zipcode'];

    $userExsisst = $db_conn->query("SELECT Username, FullName, Email, UserID FROM Users WHERE Username = '$username' AND Email = '$email' AND ZipCode = '$zipcode'");

    if($userExsisst->num_rows == 1){
      $userExsisstRow = $userExsisst->fetch_assoc();
      $PassResetID = $userExsisstRow['UserID'];
      $newpass =  uniqid();
      $newhash = hash('sha512',$newpass);

      $to       = $userExsisstRow['Email'];
      $toname   = $userExsisstRow['FullName'];
      $nick     = $userExsisstRow['Username'];
      $subject  = 'HLParty - Du har fået ny kodeord';
      $GetMailText = $db_conn->query("SELECT Message From MailMesseges Where Title = 'PasswordReset'");
      
      $GetMailTextRow = $GetMailText->fetch_assoc();
      $msg = $GetMailTextRow['Message'];
      $msg = str_replace('$toname',$toname,$msg);
      $msg = str_replace('$nick',$nick,$msg);
      $msg = str_replace('$newpass',$newpass,$msg);

      SendMail($to,$toname,$subject,$msg,$_GLOBAL);
      $db_conn->query("UPDATE Users SET PW = '$newhash' WHERE UserID = '$PassResetID'");
      //header("Location: index.php");
    }
  }else{
    $LoginUsername = $db_conn->real_escape_string($_POST["Username"]);
    $LoginPassword = $db_conn->real_escape_string($_POST["Password"]);
    $hasedPassword = hash('sha512', $LoginPassword);  
    if($LoginuserResult = $db_conn->query("SELECT * FROM Users WHERE (PW = '$hasedPassword' AND Username = '$LoginUsername')")
      ){
      $LoginuserResult->num_rows.'<br>';
      //echo hash('sha512', '1234').'<br>';
      if($LoginuserResult->num_rows == 1){
        $LoginuserRow = $LoginuserResult->fetch_assoc();
        $user_id = $LoginuserRow['UserID'];
        $_SESSION['UserID'] =  $user_id;
        $_SESSION['OneAllToken'] =  $LoginuserRow['OneallUserToken'];
        $_SESSION['Admin'] =  $LoginuserRow['Admin'];

        $LastLogin = time();
        if($db_conn->query("UPDATE Users SET LastLogin = '$LastLogin' WHERE UserID = '$user_id'")){
          header("Location: index.php");
        }
      } 
    }
  }// else end
} // isset send
?>
<div>
  <form method="post">
    <div class="form-inline ">
      <div class="form-group fallbackTextRight" style="background-color:white;">
        <label class="col-lg-6 control-lable fallbackTextRight" for="Username">Brugernavn:</label>
        <input class="col-lg-6 form-control" type="text" name="Username" id="Username">
        
        <label style="display:block;" class="col-lg-6 control-lable fallbackTextRight" id="ForPassword" for="Password">Kodeord:</label>
        <input style="display:block;" class="col-lg-6 form-control" type="password" placeholder="***" name="Password" id="Password">
        <!-- For Reset password -->
        <label style="display:none;" class="col-lg-6 control-lable fallbackTextRight" id="Foremail" for="email">Email:</label>
        <input style="display:none;" class="col-lg-6 form-control" type="email" name="email" placeholder="Lan@greenland.dk" id="email">
        
        <label style="display:none;" class="col-lg-6 control-lable fallbackTextRight" id="ForZip" for="Zipcode">Post nr:</label>
        <input style="display:none;" class="col-lg-6 form-control"  type="text" list="DBZipcodes" placeholder="1337 Awesome city" class="form-control" id="Zipcode" value="<?php if(isset($Zipcode)){echo $Zipcode;} ?>"  name="Zipcode">
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
          <!-- For Reset password end -->
        <span class="col-lg-1"></span>
        <a onclick="showStuff()" id="forgotPass" class="col-lg-5">Glemt Kodeord</a>
        <input class="col-lg-5 btn btn-success form-control" type="submit" name="Login"  id="knap" value="Logind">
        <span class="col-lg-1"></span>
        <script type="text/javascript">
        function showStuff(){
          // viseble by default
          if(document.getElementById('Password').style.display != 'block'){
            document.getElementById('Password').style.display = 'block';  
          }else{
            document.getElementById('Password').style.display = 'none';
          }
          
          if(document.getElementById('ForPassword').style.display != 'block'){
            document.getElementById('ForPassword').style.display = 'block';  
          }else{
            document.getElementById('ForPassword').style.display = 'none';
          }
          
          // hidden by default
          if(document.getElementById('Foremail').style.display != 'none'){
            document.getElementById('Foremail').style.display = 'none';
          }else{
            document.getElementById('Foremail').style.display = 'block';
          }
          if(document.getElementById('email').style.display != 'none'){
            document.getElementById('email').style.display = 'none';
          }else{
            document.getElementById('email').style.display = 'block';
          }
          
          if(document.getElementById('ForZip').style.display != 'none'){
            document.getElementById('ForZip').style.display = 'none';
          }else{
            document.getElementById('ForZip').style.display = 'block';
          }
          if(document.getElementById('Zipcode').style.display != 'none'){
            document.getElementById('Zipcode').style.display = 'none';
          }else{
            document.getElementById('Zipcode').style.display = 'block';
          }
          
          
          if(document.getElementById('knap').value != 'Logind'){
            document.getElementById('knap').value = 'Logind';
          }else{
            document.getElementById('knap').value = 'Giv mig et ny Kodeord';
          }
          
          if(document.getElementById('forgotPass').text != 'Glemt Kodeord'){
            document.getElementById('forgotPass').text = 'Glemt Kodeord';
          }else{
            document.getElementById('forgotPass').text = 'Logind';
          }
          
        }
        </script>
          <?php 
            if(isset($_POST["Login"])){
              if($_POST["Login"] != 'Logind'){
                ?>
                <br>
                <div class="alert alert-info col-lg-12">
                <?php
                echo "Der er blevet sendt en mail til dig med et nyt kodedord";
              ?>
                <script type='text/javascript'> setTimeout(
                  function() {
                      window.location = 'index.php';
                  }, 5000);
                </script>
                </div>
              <?php
              }
            }
          ?>
      </div>
    </div>
  </form>
</div>
