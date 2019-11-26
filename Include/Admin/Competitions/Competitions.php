<?php
# EventID, #GameID, #CompStart, SignupOpen, SignupClose, #MaxSignups, #TeamSize, BracketsLink, DescText, ¤Online
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }
  switch($action){
    case 'New':
      $newOrEdit = true;
      break;
    case 'Edit':
      $newOrEdit = true;
      break;
  }
} // $action end

if (isset($newOrEdit) && $newOrEdit != '') {
  include_once("Include/Admin/Competitions/CreateOrEditTournament.php");
} else {
?>
<a href="?page=Admin&subpage=Competitions&action=New#admin_menu" alt="Ny Konkurrence" type="button" class="text-center btn btn-info">Opret ny konkurrence</a>
<hr>
<table class="table table-striped table-condensed table-hover LanCMSadminmenu">
  <thead>
    <tr>
      <th class="text-center">Spil</th>
      <th class="text-center">Tunerings start</th>
      <th class="text-center">Tilmeldte</th>
      <th class="text-center">Hold størrelse</th>
      <th class="text-center">Tilmeldning</th>
      <th class="text-center">synlig</th>
      <th class="text-center">Challonge</th> <!-- Link to the Tournament on challonge -->
      <th class="text-center">Rediger</th>
      <th class="text-center">Se</th> <!-- Local Link on site -->
    </tr>
  </thead>
  <tbody>
    <?php 
    $temp = $_GLOBAL['EventID'];
    $result = $db_conn->query("SELECT * FROM Competitions WHERE EventID = '$temp'");
    while ($Comps = $result->fetch_assoc()) { #While compettitions list them
    
    # Get Matching Game Name from DB
    $TempGameID = $Comps['GameID'];
    $GameName = $db_conn->query("SELECT GameName FROM CompetitionGames WHERE GameID = '$TempGameID'");
    $GName = $GameName->fetch_assoc();
    # Get Matching Game Name from DB END
      
    ?>
    <tr>
      <td><?php echo $GName['GameName']; ?></td>
      <td>
        <?php if(time() >= $Comps['CompStart']){ ?>
        <button class="disabled btn btn-danger">Startet</button>
        <?php }else if(time() <= $Comps['CompStart']) { ?>
        <button class="btn btn-success">Åben</button>
        <?php } ?>
      </td>
      <td class="text-center">
        <?php echo '? / '.$Comps['MaxSignups']; ?>
      </td>
      <td class="text-center">
        <?php echo $Comps['TeamSize']; ?>
      </td>
      <td>
        <?php if($Comps['SignupClose'] >= time()){ ?>
        <button class="disabled btn btn-danger">Lukket</button>
        <?php }else if(time() >= $Comps['SignupOpen']) { ?>
        <button class="disabled btn btn-success">Åben</button>
        <?php } ?>
      </td>
      <td>
        <?php if($Comps['online'] == 0){ ?>
        <button class="disabled btn btn-danger">Usynlig</button>
        <?php }else{ ?>
        <button class="disabled btn btn-success">Synlig</button>
        <?php } ?>
      </td>
      <td class="text-center">
        <a href="https://challonge.com/<?= $Comps['BracketsLink']; ?>" target="_blank" class="btn btn-info">Challonge</a>
      </td>
      <td class="text-center">
        <a href="index.php?page=Admin&subpage=Competitions&action=Edit&id=<?= $Comps['ID']; ?>#admin_menu" style="display:block;" class="btn btn-warning">Rediger</a>
      </td>
      <td>
        <a href="index.php?page=Competittion=<?= $Comps['ID']; ?>" class="btn btn-primary">
          Se
        </a>
      </td>
    </tr>
    
    <?php } ?>
  </tbody>
</table>




<?php 
}
?>

