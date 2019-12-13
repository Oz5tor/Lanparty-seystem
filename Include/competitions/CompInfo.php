<?php
$CompID = mysqli_real_escape_string($db_conn,strip_tags($_GET['id']));
$comps = $db_conn->query("Select * From CompetitionGames Inner Join Competitions On Competitions.GameID = CompetitionGames.GameID
                          Inner Join Event On Competitions.EventID = Event.EventID Where ID  = $CompID LIMIT 1");
$row = $comps->fetch_assoc();
?>

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
      <div class="col-lg-3">
        <?php
          if(time() <= $row['CompStart']){
            echo '<a href="index.php?page=Competitions&action=Signup&id='.$row['ID'].'" class="container-full btn btn-success" style="display:inline;">Tilmeldning Her</a>';
          }else{
            echo '<a class="container-full btn btn-danger" style="display:inline;">Tilmeldning Lukket</a>';
          }  
          ?>
      </div>
    </div>
    <hr style="clear:both;" />
    <?= $row['DescText']; ?>  
</div>
  <div class="row">
    
    <div class="text-center col-lg-12">
      <hr>
      <iframe id="challonge" src="https://challonge.com/PostmandTest47/module?theme=7575&show_final_results=0&show_standings=0?multiplier=1&match_width_multiplier=1&subdomain=&_=157621804462" width="100%" height="600" frameborder="0" allowtransparency="true"></iframe>
    </div>
  </div>
</div>

<script type="text/javascript">

$('iframe').load(function() {
    this.style.height =
    this.contentWindow.document.body.offsetHeight + 'px';
});
  
  $('iframe').load(function() {
    setTimeout(iResize, 50);
    // Safari and Opera need a kick-start.
    var iSource = document.getElementById('challonge').src;
    document.getElementById('challonge').src = '';
    document.getElementById('challonge').src = iSource;
});
function iResize() {
    document.getElementById('challonge').style.height = 
    document.getElementById('challonge').contentWindow.document.body.offsetHeight + 'px';
}

</script>