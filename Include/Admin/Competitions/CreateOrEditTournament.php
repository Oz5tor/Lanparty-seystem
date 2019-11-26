<?php
require_once("class/ChallongeClassLib.php");

if(isset($_POST['CreateTournament'])){
  
  $MaxSignups       = $db_conn->real_escape_string($_POST['MaxSignups']);
  $TeamSize         = $db_conn->real_escape_string($_POST['TeamSize']);
  $SignUpOpen       = time($db_conn->real_escape_string($_POST['SignUpOpen']));
  $SignUpClose      = time($db_conn->real_escape_string($_POST['SignUpClose']));
  $CompStart        = time($db_conn->real_escape_string($_POST['CompStart']));
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
  $URL = time().'ID'.$_GLOBAL['EventID'].''.str_replace(array(" ","-",":"), "",$Game);
  $TournamentName   = $_GLOBAL['EventName'].' '.$Game.time();
  
  $key = "api_key=".$_GLOBAL['ChallongeApiKey'];
  $atts = "tournament[name]=$TournamentName"; # EventName + Game
  $atts .= "&tournament[subdomain]=".$_GLOBAL['ChallongeSubDomain'];
  $atts .= "&tournament[url]=$URL"; # time() + EventID + Game
  $atts = str_replace(" ", "%20", $atts);
  
  if(ChallongeFunctions::CreateTournament($atts, $key) == True){
    $db_conn->query("INSERT Competitions (EventID, GameID, CompStart, SignupOpen, SignupClose, MaxSignups, TeamSize, BracketsLink, DescText, Online)
                            VALUES ('$eventID', '$Game', '$CompStart', '$SignUpOpen', '$SignUpClose','$MaxSignups','$TeamSize','$URL', '$Desc', '$Online')");  
    header("Location: Index.php?pageAdmin&subpage=Competitions#admin_menu");
  }// If Func true end
}// Post Form End
?>
    <h3>Opret Tunering:</h3>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group col-lg-3">
          <label class="control-label" for="Game">Hvilket Spil:</label>
          <select name="Game" class="form-control">
            <option>Vælg Spil</option>
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
        <label class="control-label" for="MaxSignups">Max antal Hold</label>
        <input type="number" class="form-control" placeholder="0" id="" value=""  name="MaxSignups" required>
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="SignUpOpen">Tilmeldning Open</label>
        <div class="input-group">
          <input class="form-control picker" readonly placeholder="dd-mm-yyyy hh:mm" data-date-format="dd-mm-yyyy hh:ii"
                 <?php if(isset($SaleHasStarted)){echo 'disabled';} ?> required type="text" name="SignUpOpen" id="SignUpOpen"
                 value="<?php if(isset($EventExist)){echo date("d-m-Y H:i", $row['StartDate']);} ?>" />
          <div class="input-group-addon">&#x1f4c5;</div>
        </div>
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="SignUpClose">Tilmeldning Luk</label>
        <div class="input-group">
          <input class="form-control picker" readonly placeholder="dd-mm-yyyy hh:mm" data-date-format="dd-mm-yyyy hh:ii"
                 <?php if(isset($SaleHasStarted)){echo 'disabled';} ?> required type="datetime" name="SignUpClose"
                 id="SignUpClose" value="<?php if(isset($EventExist)){echo date("d-m-Y H:i", $row['EndDate']);} ?>" />
          <div class="input-group-addon">&#x1f4c5;</div>
        </div>
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="CompStart">Tunering Start</label>
        <div class="input-group">
          <input class="form-control picker" readonly placeholder="dd-mm-yyyy hh:mm" data-date-format="dd-mm-yyyy hh:ii"
                 <?php if(isset($SaleHasStarted)){echo 'disabled';} ?> required type="datetime" name="CompStart"
                 id="CompStart" value="<?php if(isset($EventExist)){echo date("d-m-Y H:i", $row['EndDate']);} ?>" />
          <div class="input-group-addon">&#x1f4c5;</div>
        </div>
      </div>
      <!-- ToDo: Style checkbox til Lable on/off -->
      <div class="form-group col-lg-3">
        <label class="control-label" for="TeamSize">Max antal Spiller per hold</label>
        <div class="input-group">
          <!-- Bruges kun at LanCMS og bruges ikke af Challonge API  -->
          <input class="form-control" type="number" name="TeamSize" value="1" />
        </div>  
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