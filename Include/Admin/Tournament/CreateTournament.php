<?php
require_once("class/ChallongeClassLib.php");

if(isset($_POST['CreateTournament'])){
  
  $MaxSignups       = $db_conn->real_escape_string($_POST['MaxSignups']);
  $SignUpOpen       = $db_conn->real_escape_string($_POST['SignUpOpen']);
  $SignUpClose      = $db_conn->real_escape_string($_POST['SignUpClose']);
  $CompStart        = $db_conn->real_escape_string($_POST['CompStart']);
  $RoundRobin       = $db_conn->real_escape_string($_POST['RoundRobin']);
  $ThirdplaceMatch  = $db_conn->real_escape_string($_POST['3rdplaceMatch']);
  $Desc             = $db_conn->real_escape_string($_POST['Desc']);
  $Game             = $db_conn->real_escape_string($_POST['Game']);
  $Online           = '1';
  $eventID          = $_GLOBAL['EventID'];
  $URL = time().'ID'.$_GLOBAL['EventID'].''.str_replace(" ", "%20",$Game);
  $TournamentName = $_GLOBAL['EventName'].' '.$Game;
  
  
  $key = "api_key=dkijyzF3VeQdBxxX713xO6UzofqGbAfjN2jdWHlb";
  $atts = "tournament[name]=Postman Create 40"; # EventName + Game
  $atts .= "&tournament[subdomain]=c2a3ca77a359f8164b7245c9";
  $atts .= "&tournament[url]=$URL"; # time() + EventID + Game
  $atts = str_replace(" ", "%20", $atts);
  $postFields = array(
    "game_id" => '600'
  );
  
  if(ChallongeFunctions::CreateTournament($att, $api_key, $postFields) == True){
    $db_conn->query("INSERT INTO Event (EventID, GameID, CompStart, SignupOpen, SignupClose, MaxSignups, BracketsLink, DescText, Online)
                            VALUES ('$eventID', '$Game', '$CompStart', '$SignUpOpen', '$SignUpClose','$MaxSignups','$SelectedSeatmap','$Poster')");  
  }// If Func true end
}// Post Form End
?>

    <h3>Opret Tunering:</h3>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group col-lg-3">
          <label class="control-label" for="Game">Hvilket Spil:</label>
          <select name="Game" class="form-control">
            <option>Vælg Spil</option>
            <option>Counter-Strike: Global Offensive</option>
            <option>League Of Legends</option>
          </select>
      </div>
      <div class="form-group col-lg-3">
        <label class="control-label" for="MaxSignups">Max antal Deltagere / Hold</label>
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