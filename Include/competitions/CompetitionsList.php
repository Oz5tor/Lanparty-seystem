<?php
if($action != ''){
    switch($action){
      case 'Info':
        include_once("Include/competitions/Compinfo.php");
        break;
      case 'Signup':
        include_once("Include/competitions/Signup.php");
        break;
    } 
}else{
?>
<div class="row">
  <div class="col-lg-12 LanCMSContentbox">
    <?php
      $EventID = $_GLOBAL["EventID"];
      $comps = $db_conn->query("Select * From Competitions 
      Inner Join CompetitionGames On Competitions.GameID = CompetitionGames.GameID Where EventID = $EventID Order by ID Desc");
      while($row = $comps->fetch_assoc()){
    ?>
    <div class="text-center col-lg-3 col-md-6 col-xs-6 thumbnail">
      <table class="table table-striped table-consensed">
        <tr>
          <td colspan="2">
            <center>
              <img width="125" class="" src="Images/games/<?= $row['Image']; ?>" />
            </center>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <b><?= $row['GameName'] ?> <br> <?= $row['TeamSize'] ?> VS <?= $row['TeamSize'] ?></b>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <b>
              Tunering Starter: <?php echo date('d.M - H:i', $row['CompStart'] ); ?>
            </b>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="">
            <b>Tilmeldte: ?/<?= $row['MaxSignups']; ?></b>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="">
            <?php
              if(time() <= $row['CompStart']){
                echo '<a href="index.php?page=Competitions&action=Signup&id='.$row['ID'].'" class="container-full btn btn-success">Tilmeldning Åben</a>';
              }else{
                echo '<button class="container-full btn btn-danger">Tilmeldning Lukket</button>';
              }  
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <a href="index.php?page=Competitions&action=Info&id=<?= $row['ID'] ?>" class="container-full btn btn-warning">Info <?= $row['ID'] ?></a>
          </td>
        </tr>
      </table>
    </div>
    <?php 
      }  
    ?>
  </div>
</div>
<?php 
}
?>