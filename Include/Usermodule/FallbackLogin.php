<?php
if(isset($_POST["Login"])){
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
}
?>
<div>
  <form method="post">
    <div class="form-inline fallbackloginPadding">
      <div class="form-group fallbackTextRight" style="background-color:white;">
        <label class="col-lg-6 control-lable fallbackTextRight" for="Username">Brugernavn:</label>
        <input class="col-lg-6 form-control" type="text" name="Username" id="Username">
        
        <label class="col-lg-6 control-lable fallbackTextRight" for="Password">Kodeord:</label>
        <input class="col-lg-6 form-control" type="text" name="Password" id="Password">
        
        <span class="col-lg-1"></span>
        <a href="#" class="col-lg-5">Glemt Kodeord</a>
        <input class="col-lg-5 btn btn-success form-control" type="submit" name="Login"  id="Login" value="Logind">
        <span class="col-lg-1"></span>
        
      </div>
    </div>
  </form>
</div>
