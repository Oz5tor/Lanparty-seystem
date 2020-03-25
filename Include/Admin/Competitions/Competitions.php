<?php
# EventID, #GameID, #CompStart, SignupOpen, SignupClose, #MaxSignups, #TeamSize, BracketsLink, DescText, ¤Online
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }
  switch($action){
    case 'NewT':
      $newOrEdit = true;
      break;
    case 'EditT':
      $newOrEdit = true;
      break;
    case 'NewG':
      $newOrEditG = true;
      break;
  }
} // $action end

if (isset($newOrEdit) && $newOrEdit != '') {
  include_once("Include/Admin/Competitions/CreateOrEditTournament.php");
} else if(isset($newOrEditG)){
  include_once("Include/Admin/Competitions/CreateNewGame.php");
}
else {
  
function ChallongeStarted ($ApiKey, $ChallongeLink){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.challonge.com/v1/tournaments/$ChallongeLink/matches.json?api_key=$ApiKey",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);
if($response == "[]"){
  return false;
}else {return true;}
  
  
}
?>
  <div class="col-lg-6 col-sm-6 col-xs-6">
    <a href="?page=Admin&subpage=Competitions&action=NewT#admin_menu" alt="Ny Konkurrence" type="button" class="text-center btn btn-info">Opret ny konkurrence</a>
  </div>
  
  <div class="col-lg-6 col-sm-6 col-xs-6">
    <a href="?page=Admin&subpage=Competitions&action=NewG#admin_menu" alt="Nyt Spil" type="button" class="text-center btn btn-info">Opret nyt spil</a>
  </div>
<br />

<hr>
  <div class="col-lg-12 text-center alert alert-info" role="alert">
    Der vises kun Tuneringer for aktuelt Event ( <?= $_GLOBAL['EventName']; ?> )
  </div>
<table class="table table-striped table-condensed table-hover LanCMSadminmenu">
  <thead>
    <tr>
      <th class="text-center">Spil</th>
      <th class="text-center">Tunerings start</th>
      <th class="text-center">Tilmeldte</th>
      <th class="text-center">Hold Størrelse</th>
      <th class="text-center">Tilmeldning</th>
      <th class="text-center">synlig</th>
      <th class="text-center">Challonge</th> <!-- Link to the Tournament on challonge -->
      <th class="text-center">Rediger</th>
      <th class="text-center">Mere</th>
      <!-- "mere" trigger collabseble box to see more Actions -->
    </tr>
  </thead>
  <tbody>
    <?php 
    $temp = $_GLOBAL['EventID'];
    $result = $db_conn->query("SELECT * FROM Competitions WHERE EventID = '$temp'");
    while ($Comps = $result->fetch_assoc()) { #While compettitions list them
    
    # Get Matching Game Name from DB
    $TempGameID = $Comps['GameID'];
    $GameName = $db_conn->query("SELECT * FROM CompetitionGames WHERE GameID = '$TempGameID'");
    $GName = $GameName->fetch_assoc();
    # Get Matching Game Image from DB END
        
  #echo 'CompStart'.$Comps['CompStart'].'<br>';
  #echo 'SignupClose'.$Comps['SignupClose'].'<br>';
  #echo 'SignupOpen'.$Comps['SignupOpen'].'<br>';
    ?>
    
    <tr>
      <td align="center">
        <img class=" img-responsive" width="35" src="Images/games/<?= $GName['Image']; ?>"/>
      </td>
      <td>
        <?php if(time() >= $Comps['CompStart']){ ?>
        <button class="disabled btn btn-danger">
          Startet: <?php echo date("d M Y - H:i", $Comps['CompStart']); ?>
        </button>
        <?php }else if(time() <= $Comps['CompStart']) { ?>
        <button class="btn btn-success">
          Starter: <?php echo date("d M Y - H:i", $Comps['CompStart']); ?>
        </button>
        <?php } ?>
      </td>
      <td class="text-center">
        <?php echo '? / '.$Comps['MaxSignups']; ?>
      </td>
      <td class="text-center">
        <?php echo $Comps['TeamSize']; ?>
      </td>
      <td>
        <?php if(time() >= $Comps['SignupClose'] ){ ?>
        <button class="disabled btn btn-danger">Lukket</button>
        <?php }else if(time() >= $Comps['SignupOpen']  ) { ?>
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
        <a href="index.php?page=Admin&subpage=Competitions&action=EditT&id=<?= $Comps['ID']; ?>#admin_menu" style="display:block;" class="btn btn-warning">Rediger</a>
      </td>
      <td>
        <button class="btn btn-primary" data-toggle="collapse" data-target="#Comp<?= $Comps['ID']; ?>">
          Mere
        </button>
      </td>
    </tr>
    <tr>
      <td colspan="9">
        <div id="Comp<?= $Comps['ID']; ?>" class="collapse">
              <div class="row">
                  <div class="col-lg-3">
                    <button class=" btn btn-info" data-toggle="collapse" data-target="#DescComp<?= $Comps['ID']; ?>" >Tunernings Beskrivelse</button>
                  </div>
                  <div class="col-lg-3">
                    <button class=" btn btn-info" data-toggle="collapse" data-target="#Teams" >Tilmeldte Teams</button>
                  </div>
                
                <?php
                  $Akey = $_GLOBAL['ChallongeApiKey'];
                  $Link  = $Comps['BracketsLink'];
                  $Sub   = $_GLOBAL['ChallongeSubDomain'];
                  $ChallongeStarted = ChallongeStarted($Akey, $Sub.'-'.$Link);
                  
                  if($ChallongeStarted == true){
                    ?>
                    <div class="col-lg-3">
                      <button disabled class=" btn btn-primary">Send tilmelte hold til Challonge</button>
                    </div>
                    <div class="col-lg-3">
                      <button disabled class=" btn btn-primary">Start Tunering i challonge systemet</button>
                    </div>
                    <?php
                  }else {
                    ?>
                    <div class="col-lg-3">
                      <button class=" btn btn-primary">Send tilmelte hold til Challonge</button>
                    </div>
                    <div class="col-lg-3">
                      <button class=" btn btn-primary">Start Tunering i challonge systemet</button>
                    </div>
                    <?php
                  }
                ?>
              </div>
          <hr>
              <div class="row">
                <div class="col-lg-12">
                  <div id="DescComp<?= $Comps['ID']; ?>" class="collapse">
                  <?= $Comps['DescText']; ?>
                  <hr>
                  </div>
                </div>
              </div>
              <!-- class="collapse" -->
              <div id="Teams" class="collapse">
              <div class="row">
                  <div class="col-lg-3">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#teamx" >Static Team</button>
                    <div id="teamx" class="collapse">Static Team</div>
                  </div>
                  <div  class="col-lg-3">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#teamx1">Static Team</button>
                    <div id="teamx1" class="collapse">Static Team</div>
                  </div>
                  <div  class="col-lg-3">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#teamx2">Static Team</button>
                    <div id="teamx2" class="collapse">Static Team</div>
                  </div>
                  <div  class="col-lg-3">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#teamx3">Static Team</button>
                    <div id="teamx3" class="collapse">Static Team</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-3">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#teamx4" >Static Team</button>
                    <div id="teamx4" class="collapse">Static Team</div>
                  </div>
                  <div  class="col-lg-3">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#teamx5">Static Team</button>
                    <div id="teamx5" class="collapse">Static Team</div>
                  </div>
                  <div  class="col-lg-3">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#teamx6">Static Team</button>
                    <div id="teamx6" class="collapse">Static Team</div>
                  </div>
                  <div  class="col-lg-3">
                    <button class="btn btn-info" data-toggle="collapse" data-target="#teamx7">Static Team</button>
                    <div id="teamx7" class="collapse">Static Team</div>
                  </div>
              </div>
                <hr>
              </div> <!-- End of signed teams colapse -->
              
                  <?php
                    #if((time() >= $Comps['CompStart']) && ($ChallongeStarted == true)){
                  ?>
                  <div class="row">
                    <iframe frameborder="0" class="col-lg-12" height="600" src="https://challonge.com/<?= $Comps['BracketsLink']; ?>/module?theme=7575&show_final_results=1"></iframe>
                  </div>
                  <?php
                    #} // end if comp is started
                  ?>          
        </div>
      </td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php 
}
?>

