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

<div class="row LanCMScontentbox align-items-center">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-7 ">
  <img height="75" src="<?php echo $PictureUrl; ?>">
    <?php
#    echo "Hej ".$PreffereredUsername."<br>";
#    echo $FullName;
#    echo $Clan;
    ?>
  </div>
  <div class="col-lg-10 col-md-10 col-sm-10 col-xs-7  ">
      <a class="btn btn-info" style="margin-bottom: 10px;" href="">Min billet</a>
      <a class="btn btn-primary" style="margin-bottom: 10px;" href="?page=EditMyProfile">Ret min profil</a>
      <a class="btn btn-danger" style="margin-bottom: 10px;" href="?action=LogOut">Logud</a>
  <?php if(isset($_SESSION['Admin'])){ ?> 
      <a class="btn btn-warning" style="margin-bottom: 10px;" href="?page=Admin">Admin</a>
      <?php } ?>
      <p>
      <?php 
          echo "Hej ".$PreffereredUsername.".";
      ?>
      </p> 
  </div>  
</div>


