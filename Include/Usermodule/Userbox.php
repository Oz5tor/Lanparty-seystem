<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-7">
    <ul>
      <?php if($_SESSION['Admin'] == 1){ ?> 
      <li><a href="?page=Admin">Administration</a></li>
      <?php } ?>
      <li><a href="?page=EditMyProfile">Ret min profil</a></li>
      <li><a href="">Min billet</a></li>
      <li><a href="?action=LogOut">Logud</a></li>
    </ul>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
    <?php
    $UserID = $_SESSION['UserID'];
    if($result = $db_conn->query("SELECT * FROM Users WHERE UserID = '$UserID'")){
      if($result -> num_rows){
        $row = $result->fetch_assoc();
        $PreffereredUsername    = $row['Username'];
        $FullName               = $row['FullName'];
        $Clan                   = $row['ClanID'];
        if($row['ProfileIMG'] == ''){ $PictureUrl = "Images/Users/nopic.png";} else { $PictureUrl = $row['ProfileIMG']; }
      }
    }
    ?>
    
    <img height="80" src="<?php echo $PictureUrl; ?>">
  </div>
</div>


