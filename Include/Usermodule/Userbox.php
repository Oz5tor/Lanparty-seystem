<?php
    $UserID = $_SESSION['UserID'];
    if($result = $db_conn->query("SELECT * FROM Users WHERE UserID = '$UserID'")){
      if($result -> num_rows){
        $row = $result->fetch_assoc();
        $PreffereredUsername    = $row['Username'];
        $FullName               = $row['FullName'];
        $Clan                   = $row['ClanID'];
        if($row['ProfileIMG'] == ''){ $PictureUrl = "Images/Users/nopic.png";} else { $PictureUrl = "Images/Users/nopic.png"; }
      }
    }
    ?>

<div class="row LanCMScontentbox">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-7 ">
  <img height="75" src="<?php echo $PictureUrl; ?>">
    <?php
#    echo "Hej ".$PreffereredUsername."<br>";
#    echo $FullName;
#    echo $Clan;
    ?>
  </div>
  <div class="col-lg-9 col-md-10 col-sm-10 col-xs-7 col-lg-offset-1 ">
      <a class="btn btn-info" style="margin-bottom: 5px;" href="">Min billet</a>
      <a class="btn btn-primary" style="margin-bottom: 5px;" href="?page=EditMyProfile">Ret min profil</a>
      <a class="btn btn-danger" style="margin-bottom: 5px;" href="?action=LogOut">Logud</a>
  <?php if($_SESSION['Admin'] == 1){ ?> 
      <a class="btn btn-warning" style="margin-bottom: 5px;" href="?page=Admin">Administration</a>
      <?php } ?>
    
    
    
    
    
  </div>  
</div>


