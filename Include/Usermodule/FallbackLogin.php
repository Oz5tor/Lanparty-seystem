<?php
if(isset($_POST["Login"])){
  $LoginUsername = $db_conn->real_escape_string($_POST["Username"]);
  $LoginPassword = $db_conn->real_escape_string($_POST["Password"]);
  
  $hasedPassword = hash('sha512', $LoginPassword);
  
  if($LoginuserResult = $db_conn->query("SELECT UserID, Admin, OneallUserToken, Username, Password FROM Users 
                                        WHERE Password = '$hasedPassword' AND
                                        Username = '$LoginUsername'")
    ){
    if($LoginuserResult->num_rows >= 1){
      $LoginuserRow = $LoginuserResult->fetch_assoc();
      $user_id = $LoginuserRow['UserID'];
      $_SESSION['UserID'] =  $user_id;
      $_SESSION['OneAllToken'] =  $LoginuserRow['OneallUserToken'];
      $_SESSION['Admin'] =  $LoginuserRow['Admin'];

      $LastLogin = time();
      if($db_conn->query("UPDATE Users SET LastLogin = '$LastLogin' WHERE UserID = '$user_id'")){
        //header("Location: index.php");
      }
    } 
  }
}
?>
<form method="post">
  <div class="form-inline">
    <div class="form-group">
      <div class="col-lg-6">
        <label class="control-lable" for="Username">Brugernavn:</label>
      </div>
      <div class="col-lg-6">
        <input class="form-control" type="text" name="Username" id="Username">
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-6">
        <label class="control-lable" for="Password">Kodeord:</label>
      </div>
      <div class="col-lg-6">
        <input class="form-control" type="text" name="Password" id="Password">
      </div>
    </div>
    <div class="form-group col-lg-6">
      <a href="#" class="btn btn-primary" style="display: block;">Glemt Kodeord</a>
    </div>
    <div class="form-group col-lg-6">
      <input type="submit" name="Login" id="Login" value="Logind" class="btn btn-success form-control" style="display: block;">
    </div>
  </div>
</form>