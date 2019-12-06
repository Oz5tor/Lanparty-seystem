<?php 
if(isset($_POST['CreateGame'])){
  if($_FILES['Poster']['error'] != 4){
    $AllowedFileTypeArray = array('jpg','png','gif','JPEG');
    $GImage = ImageUploade('GameImage','Images/games',$AllowedFileTypeArray);
  }
  $GameName = $db_conn->real_escape_string($_POST['GameName']);
  
  $db_conn->query("Insert Into CompetitionGames (GameName, Image ) VALUES ('$GameName','$GImage')");
  header("Location: index.php?page=Admin&subpage=Competitions&action=NewG"); 
}
?>

<h3>Opret Nyt Spil</h3>
<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group col-lg-4">
    <label class="control-label" for="">Spillets Fulde Navn:</label>
    <div class="input-group">
      <input name="GameName" required placeholder="Counter-Strike: Global Offensive" class="form-control"/>
    </div>
  </div>
  
  <div class="form-group col-lg-5">
    <label class="control-label" for="">
      Billedet for dette Spil (brede og højde skal være den samme):
    </label>
    <div class="input-group">
      <input type="file" name="GameImage" required class="form-control"/>
    </div>
  </div>
  
  <div class="form-group col-lg-3">
    <label class="control-label" for=""></label>
    <div class="input-group">
        <input class="btn btn-primary" type="submit" value="Opret Spil" name="CreateGame">
      </div>
  </div>
</form>


<!-- List of all know Games -->
<table class="table table-striped table-condensed table-hover LanCMSadminmenu">
  <thead>
    <tr>
      <th class="text-center">Ikon:</th>
      <th class="text-center">Spil Navn</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $Games = $db_conn->query("SELECT * FROM CompetitionGames ORDER BY GameID DESC");
    while($Game = $Games->fetch_assoc()){
    ?>
    <tr align="center">
      <td>
        <img class=" img-responsive" width="35" src="Images/games/<?= $Game['Image']; ?>"/>
      </td>
      <td><?php echo $Game['GameName']; ?></td>
    </tr>
    <?php
    }
    ?>
    
  </tbody>
</table>