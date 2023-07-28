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


    ## Get seat number
    $Userbox_HasTicket = 0;

    if(isset($_GLOBAL['EventID'])){
      $checkSeat = $db_conn->query("SELECT SeatNumber FROM tickets WHERE RevokeDate IS NULL AND EventID = '".$_GLOBAL['EventID']."' AND UserID = '".$_SESSION['UserID']."' ");
      if($checkSeat -> num_rows){
        $Myseat = $checkSeat->fetch_assoc();
        #echo $Myseat["SeatNumber"];
      }
      
    }


    ?>

<div class="row LanCMScontentbox">
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
  <img class="img-responsive" src="<?php echo $PictureUrl; ?>">

  </div>
  <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
    <div class="row">
      <a class="btn btn-primary" role="button" href="?page=EditMyProfile">Min profil</a>
    <?php if(isset($_SESSION['Admin']) && ($_SESSION['Admin'] == 1)){ ?> 
      <a class="btn btn-warning" role="button" href="?page=Admin">Admin</a>
    <?php } ?>
      <a class="btn btn-danger" role="button" href="?action=LogOut">Logud</a>
    </div>
    <div class="row">
      <?php 
            echo "Hej ".$PreffereredUsername.". ";
            if(isset($_GLOBAL["EventID"])){
              if(isset($Myseat["SeatNumber"])){
                echo "| Din plads er: ".$Myseat["SeatNumber"];
              }
            }
        ?>
    </div> 
  </div>  
</div>




