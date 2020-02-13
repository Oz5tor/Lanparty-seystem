<?php
require_once("class/GetUsernameFromID.php");
$CompID = mysqli_real_escape_string($db_conn,strip_tags($_GET['id']));
$comps = $db_conn->query("Select * From CompetitionGames Inner Join Competitions On Competitions.GameID = CompetitionGames.GameID
                          Inner Join Event On Competitions.EventID = Event.EventID Where ID  = $CompID LIMIT 1");
$row = $comps->fetch_assoc();

$MaxTeamSize = $row['TeamSize'];

# ====================================================================================================
# ====================================================================================================
# ====================================================================================================


if(isset($_POST['Save'])){
  
  $TeamName       = $db_conn->real_escape_string($_POST['TeamName']);
  $TeamMembers    = $_POST['TeamList'];
  $PostCount = count($TeamMembers);
  
  
  
  # =============== Insert Team and Team Members Start =======================
  $db_conn->query("Insert into CompTeams (TeamName, CompID) VALUES ('$TeamName', '$CompID')");
  $GetTeamID = $db_conn->query("Select TeamID From CompTeams Where TeamName = '$TeamName' AND CompID = '$CompID'");
  $TempTeamID = $GetTeamID->fetch_assoc();
  $TempTeamID = $TempTeamID['TeamID'];  
  foreach($TeamMembers as $Player){
    echo $Player.'<br>';
    $db_conn->query("Insert into CompTeamsMember (TeamID, UserName) VALUES ('$TempTeamID', '$Player')");
  }
  # =============== Insert Team and Team Members End =======================
  
  
} // end of post

?>
 <!-- =========== Competittions Info -->
<div class="row thumbnail">
  <div class="col-lg-4 LanCMSContentbox text-center">
    <img width="" class="" src="Images/games/<?= $row['Image']; ?>" />
  </div>
  <div class="col-lg-8 LanCMSContentbox">
    <h2 style="">
      <?= $row['GameName'].' '.$row['TeamSize'].' VS '.$row['TeamSize'].' '; ?>
    </h2>
      <h5 style="float:left;">Event: <?= $row['Title']; ?></h5>
    <hr style="clear:both;" />
    <div class="row">
      <div class="col-lg-3"><b>Tilmeldte: ?/<?= $row['MaxSignups']; ?></b></div>
      <div class="col-lg-4">
            <b>
              Tunering Starter: <?php echo date('d.M - H:i', $row['CompStart'] ); ?>
            </b>
      </div>
      <div class="col-lg-2"></div>
      <div class="col-lg-3"></div>
    </div>
    <hr style="clear:both;" />
    <?= $row['DescText']; ?>  
  </div>
  
  <div class="col-lg-12">
    <hr />
    <?php require_once("Include/MsgUser.php"); ?>
    <?php if ($MaxTeamSize == 1){ ?>
    <?php }else { ?>
    <form action="" method="post" enctype="multipart/form-data">
    <div class="form-group form-inline col-lg-3 col-lg-offset-3">
      <label for="TeamName">*Hold Navn: </label>
      <input class="form-control" type="text" id="TeamName" value="" required name="TeamName">
    </div>
  <!-- =================== List of useres there can be added to the team ======================= -->
  <div class=" col-lg-3">
    <label class="control-label" for="Person">Hold Medlem:</label>
    <input class="form-control" List="Users" type="text" id="Person" value="" name="Person">
    <datalist id="Users">
      <?php
        $tempUserID = $_SESSION['UserID'];
        $GetUsers = $db_conn->query("Select * From Tickets 
                                      Inner Join Users On Tickets.UserID = Users.UserID 
                                      Inner Join CompTeamsMember On Users.Username = CompTeamsMember.UserName
                                      Inner Join CompTeams On CompTeamsMember.TeamID = CompTeams.TeamID
                                      Where Tickets.EventID = 2 And Users.UserID Not Like $tempUserID ");
        while($UsersRows = $GetUsers->fetch_assoc()){
        ?>
      <option  value="<?php echo $UsersRows['Username']; ?>"><?php echo $UsersRows['Username']; ?></option>
        <?php
        }
      ?>
    </datalist>
  </div>
    <!-- ================== Team Member List ======================== -->
  <div class="row">
  <div class=" col-lg-6 col-lg-offset-3">
    <input type="button" name="add" id="btn_AddToCrew" value="Tilføj Til Holdet" class="btn btn-success form-control" />
    <select size="5" required class="form-control" name="TeamList[]" id="TeamMembers" multiple="multiple">
      <?php 
      if(isset($TeamMembers)){
        foreach($TeamMembers as $x){
          echo "<option value='$x'>$x</option>";
        }
      }else {
          $TempUName = TorGetUserName($_SESSION['UserID'], $db_conn);
          echo "<option value='$TempUName'>$TempUName</option>"; 
      }
      ?>
    </select>
    <input type="button" name="add" id="btn_RemoveFromCrew" value="Fjern Fra Holdet" class="btn btn-danger form-control" />
  </div>
  </div>
  <!-- ========================================== -->
  <script type="text/javascript">
      $(function(){
        $("#btn_AddToCrew").click(function(){
          var user = $('#Person').val(); // input field
          //var Xperson = document.getElementById("Users"); // datalist
          if(user != ""){
            // add the price group to the list
            $('#TeamMembers').append('<option value="'+user+'">'+user+'</option>');
            // zero the used fields
            //Xperson.children[0].remove() // for the futuere where added team members get fromoved from the datalist            
            $('#Person').val('').focus();
          }
        });

        $('#btn_RemoveFromCrew').click(function(){
          var cr = confirm('Er du sikker på du vil fjerne de valgte fra listen?');
          if(cr == true){
           $('#TeamMembers > option:selected').each(function(){
            $(this).remove();
          })
          }
        });
      });
    </script>
  <!-- ========================================== -->
      <div class="row">
        <div class="text-center col-lg-4 col-lg-offset-4">
          <br>
          
        <script type="text/javascript">
          function selectAll()
            {
              selectBox = document.getElementById("TeamMembers");
              var x = document.getElementById("TeamMembers").length;  
              if (x < <?= $MaxTeamSize; ?>){
                  alert("Der er ikke nok Spillere på holdet");
                  }else if (x > <?= $MaxTeamSize; ?>){
                    alert("Der er for mange Spillere på holdet");
              }else  {
                for (var i = 0; i < selectBox.options.length; i++)
                { selectBox.options[i].selected = true; }
              }
            }
        </script>
        <input class="btn btn-primary " type="submit" value="Tilmeld Holdet" name="Save" onclick="selectAll()" />
          <br>&nbsp;
        </div>
      </div>
    </form>
    <?php } ?>
  </div>
  
</div>