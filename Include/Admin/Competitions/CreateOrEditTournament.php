<?php
require_once("class/ChallongeClassLib.php");

if(isset($action) == 'EditT'){
 # $tempID = $db_conn->real_escape_string($_GET['id']);
 # $query = "SELECT * FROM Competitions WHERE ID = $tempID LIMIT 1";
}


if(isset($_POST['CreateTournament'])){
  
  $MaxSignups       = $db_conn->real_escape_string($_POST['MaxSignups']);
  $TeamSize         = $db_conn->real_escape_string($_POST['TeamSize']);
  $SignUpOpen       = strtotime(str_replace('/', '-',$_POST['SignUpOpen']));
  $SignUpClose      = strtotime(str_replace('/', '-',$_POST['SignUpClose']));
  $CompStart        = strtotime(str_replace('/', '-',$_POST['CompStart']));

  if(isset($_POST['RoundRobin'])){
    $RoundRobin = $db_conn->real_escape_string($_POST['RoundRobin']);
  }
  if(isset($_POST['3rdplaceMatch'])){
    $ThirdplaceMatch = $db_conn->real_escape_string($_POST['3rdplaceMatch']);
  }
  $Desc             = $db_conn->real_escape_string($_POST['Desc']);
  $Game             = $db_conn->real_escape_string($_POST['Game']);
  $Online           = '1';
  $eventID          = $_GLOBAL['EventID'];
  $URL              = time().'ID'.$_GLOBAL['EventID'].''.str_replace(array(" ","-",":"), "",$Game);
  $TournamentName   = $_GLOBAL['EventName'].' '.$Game.time();
  
  $key = "api_key=".$_GLOBAL['ChallongeApiKey'];
  $atts = "tournament[name]=$TournamentName"; # EventName + Game
  $atts .= "&tournament[subdomain]=".$_GLOBAL['ChallongeSubDomain'];
  $atts .= "&tournament[url]=$URL"; # time() + EventID + Game
  $atts = str_replace(" ", "%20", $atts);
  #echo "<pre>";
  #print_r($atts);
  #print_r(ChallongeFunctions::CreateTournament($atts, $key));
  #echo "</pre>";
  
  if(($SignUpClose <= $SignUpOpen) || ($CompStart <= $SignUpOpen)){
    echo '<div class="alert text-center alert-warning" role="alert">Hov Tjek Lige Datoerne </div>';
  }else if(true){ #if(ChallongeFunctions::CreateTournament($atts, $key) == True)
    $db_conn->query("INSERT Competitions (EventID, GameID, CompStart, SignupOpen, SignupClose, MaxSignups, TeamSize, BracketsLink, DescText, Online)
                            VALUES ('$eventID', '$Game', '$CompStart', '$SignUpOpen', '$SignUpClose','$MaxSignups','$TeamSize','$URL', '$Desc', '$Online')");  
    header("Location: Index.php?pageAdmin&subpage=Competitions#admin_menu");
  }// If Func true end
  else{
    echo '<div class="alert alert-info" role="alert">Tuneringen Blev ikke Oprettet</div>';
  }
}// Post Form End
?>
    <h3>Opret Tunering:</h3>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group col-lg-3">
          <label class="control-label" for="Game">Hvilket Spil:</label>
          <select required name="Game" class="form-control">
            <option value="">Vælg Spil</option>
            <?php
              $GamesReuslt = $db_conn->query("Select * From CompetitionGames");
              while ($Games = $GamesReuslt->fetch_assoc()){
            ?>
              <option value="<?php echo $Games['GameID']; ?>">
                <?php echo $Games['GameName']; ?>
              </option>
              <?php
              }
            ?>
          </select>
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="CompStart">Tunering Start</label>
        <div class="input-group">  
          <input type="text" class="form-control datetimepicker-input datetimepicker1" id="CompStart" placeholder="dd-mm-yyyy hh:mm" data-toggle="datetimepicker" data-target="#CompStart" required  name="CompStart"  value="<?php if(isset($_POST['CompStart'])){echo $_POST['CompStart'];} ?>" />          
          <div class="input-group-addon">&#x1f4c5;</div>
        </div>
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="SignUpOpen">Tilmeldning Open</label>
        <div class="input-group">
          <input autofocus="autofocus" type="text" class="form-control datetimepicker-input datetimepicker1" id="SignUpOpen" placeholder="dd-mm-yyyy hh:mm" data-toggle="datetimepicker" data-target="#SignUpOpen" required  name="SignUpOpen"  value="<?php if(isset($_POST['SignUpOpen'])){echo $_POST['SignUpOpen'];} ?>" />
          <div class="input-group-addon">&#x1f4c5;</div>
        </div>
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="SignUpClose">Tilmeldning Luk</label>
        <div class="input-group">
          <input class="form-control datetimepicker1" placeholder="dd/mm/yyyy hh:mm" required type="text" name="SignUpClose" id="SignUpClose" value="" data-target="#SignUpClose"	data-toggle="datetimepicker" />
          <div class="input-group-addon">&#x1f4c5;</div>
        </div>
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="MaxSignups">Max antal Hold</label>
        <input type="number" class="form-control" placeholder="1" id="" value="<?php if(isset($_POST['MaxSignups'])){echo $_POST['MaxSignups'];} ?>"  name="MaxSignups" required>
      </div>
      <!-- ToDo: Style checkbox til Lable on/off -->
      <div class="form-group col-lg-3">
        <label class="control-label" for="TeamSize">Max antal Spiller per hold</label>
        <!-- Bruges kun at LanCMS og bruges ikke af Challonge API  -->
          <input class="form-control" type="number" placeholder="1" name="TeamSize" value="<?php if(isset($_POST['TeamSize'])){echo $_POST['TeamSize'];} ?>" />
        
      </div>    
      <!-- ToDo: Style checkbox til Lable on/off -->
      <div class="form-group col-lg-3">
        <label class="control-label" for="RoundRobin">Round Robin</label>
        <div class="input-group">
          <input class="form-control" type="checkbox" name="RoundRobin" />
        </div>  
      </div>    
      <!-- ToDo: Style checkbox til Lable on/off -->
      <div class="form-group col-lg-3">
        <label class="control-label" for="3rdplaceMatch">3# Placerings Kamp?</label>
        <div class="input-group">
          <input class="form-control" type="checkbox" name="3rdplaceMatch" />
        </div>  
      </div>
      <div class="form-group col-lg-12">
        <label class="control-label" for="Desc">Beskrivelse (Regler mm)</label>
        <div class="input-group">
          <textarea class="from-control" id="AdminTinyMCE" name="Desc"></textarea>
        </div>  
      </div>
      <div class="form-group col-lg-12">
        <input class="btn btn-primary" type="submit" value="Opret Tunering" name="CreateTournament">
      </div>
    </form>