<?php
if(!isset($action)) { header("Include/Admin/index.php"); }
if(isset($_GET['id']) && $_GET['id'] != '') {
  $SeatmapID = $db_conn->real_escape_string($_GET['id']);
}
if(!empty($_POST)) {
  $fullString = trim(preg_replace('/\s+/', '', $_POST['generate-seat-map']));
  $width = strlen(preg_split('/\s+/', $_POST['generate-seat-map'])[0]);
  $availableSeats = preg_match_all('/(a)/', $fullString);
  $crewSeats = preg_match_all('/(c)/', $fullString);

  if(isset($action) AND $action == "Edit") {
    $query = "UPDATE hlparty.Seatmap
        SET Seatmap.SeatString = ".$fullString.", Seatmap.Width = ".$width.",
            Seatmap.Seats = ".$availableSeats.", Seatmap.CrewSeats = ".$crewSeats."
        WHERE Seatmap.SeatmapID = ".$SeatmapID.";";
  } elseif (isset($action) AND $action == "New") {
    $query = "INSERT INTO  hlparty.Seatmap (
        Width, SeatString,
        Seats, CrewSeats
        ) VALUES (
          '".$width."',  '".$fullString."',
          '".$availableSeats."',  '".$crewSeats."'
        );";
  }
  $db_conn->query($query);
  header("Location: index.php?page=Admin&subpage=Seatmap");
}
?>
<div class="col-lg-7">
  <!-- Instructions - How to make a seatmap. -->
  <h3>Instruktioner</h3>
  <p>Lav et seatmap ved at udfylde nedenstående betegnelser i boksen til højre.</p>
  <div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Betegnelser</h3>
  </div>
  <div class="panel-body">
    <ul class="hlpf_admin_seatmap">
      <li><code>a</code> En plads der er tilgængelig / kan købes.</li>
      <li><code>c</code> En plads der kun er til crew / kan ikke købes.</li>
      <li><code>k</code> Kiosk / kantine område.</li>
      <li><code>A</code> Arkade. Spille maskiner og andet.</li>
      <li><code>s</code> Scene / Podie.</li>
      <li><code>_</code> Fri rum.</li>
    </ul>
  </div>
</div>
<p><small>Note: Alle linjer skal have samme længde! Fyld resten af en linje med <code>_</code> hvis det er nødvendigt.</small></p>
</div>
<div class="form-group col-lg-5">
  <?php if (isset($action) AND $action == "Edit" AND isset($SeatmapID)){
    // If editing, show this
    echo "<h3>Ændre seatmap med ID: $SeatmapID</h3>";
  } else { echo "<h3>Nyt seatmap</h3>"; } ?>
  <form action="" method="POST">
    <textarea id="generate-seat-map" class="hlpf_seatmap-gen" name="generate-seat-map" rows="10" cols="50" autofocus><?php // Keep this PHP-tag close to the textarea!
    if ($action == "Edit" AND !empty($SeatmapID)) {
      // If we are editing a seatmap...
      $result = $db_conn->query("SELECT * FROM Seatmap WHERE SeatmapID = $SeatmapID");
      if (!empty($result)) {
        // Fetch the results of the seatmap
        $row = $result->fetch_assoc();
        // Saving this output since we will be overriding it later.
        $SeatString = $row['SeatString'];
        $correction = 0;
        for ($i=$row['Width']; $i < strlen($SeatString); $i += $row['Width']) {
          // Inset newline at [width]*[iteration]+[correction]
          $SeatString = substr_replace($SeatString, "\n", $i+$correction, 0);
          $correction += 1;
        }
        echo $SeatString;
        // Quick garbage colletion...
        unset($correction, $SeatString, $result, $row);
      }
    }
    ?></textarea>
    <br>
    <a class="btn btn-primary" onclick="generatePreview()">Preview</a>
    <button class="btn btn-primary" type="submit">Save</button>
  </form>
</div>
<script src="JS/seat-charts/jquery.seat-charts.min.js"></script>
<div class="col-lg-12" id="View-seatmap"></div>
<script type="text/javascript">
  function generatePreview() {
    // On the ID "View-seatmap": load 'page', 'data':'someDataHere'
    $('#View-seatmap')
      .load(
        'Include/Admin/Seatmap/showseatmap.php',
        // Send some data with the loader, called "generated_seatmap".
        // Grab this with $_REQUEST['generated_seatmap']
        {'generated_seatmap': document.getElementById('generate-seat-map').value }
    )
  }
</script>
