<?php
if(!isset($action)) { header("Include/Admin/index.php"); }
if(isset($_GET['id']) && $_GET['id'] != '') {
  $SeatmapID = $db_conn->real_escape_string($_GET['id']);
}
if(!empty($_POST)) {
  $fullString = trim(preg_replace('/\s+/', '', $_POST['generate-seat-map']));
  $SeatmapName = $_POST['Name'];
  $width = strlen(preg_split('/\s+/', $_POST['generate-seat-map'])[0]);
  $availableSeats = substr_count($fullStrin, "a");
  $crewSeats = substr_count($fullStrin, "c");

  if(isset($action) AND $action == "Edit") {
    $query = "UPDATE hlparty.Seatmap
        SET Seatmap.SeatString = '".$fullString."', Seatmap.Width = ".$width.",
            Seatmap.Seats = ".$availableSeats.", Seatmap.CrewSeats = ".$crewSeats."
        WHERE Seatmap.SeatmapID = ".$SeatmapID.";";
  } elseif (isset($action) AND $action == "New") {
    $query = "INSERT INTO  hlparty.Seatmap (
        SeatString, Name, Width,
        Seats, CrewSeats
        ) VALUES (
          '".$fullString."', '".$SeatmapName."' '".$width."',
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
<p><small>Note: Alle linjer skal have samme længde! Fyld resten af en linje med
<code>_</code> hvis det er nødvendigt.</small></p>
</div>
<div class="col-lg-5">
  <?php if (isset($action) AND $action == "Edit" AND isset($SeatmapID)){
    // If editing, show this
    echo "<h3>Ændre seatmap med ID: $SeatmapID</h3>";
  } else { echo "<h3>Nyt seatmap</h3>"; } ?>
  <form action="" method="POST">
    <div class="form-group">
      <textarea id="generate-seat-map" class="form-control hlpf_seatmap-gen"
            name="generate-seat-map" rows="10" autofocus><?php // Keep this PHP-tag close to the textarea!
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
        }
      }
      ?></textarea>
    </div>
    <div class="form-group">
      <input type="text" class="form-control" id="SeatmapName" name="SeatmapName"
            placeholder="Navn til seatmap" value="<?php if (isset($row['Name'])) {
              echo $row['Name']; } ?>">
    </div>
    <div class="form-group">
      <a class="btn btn-primary" onclick="generatePreview()">Preview</a>
      <?php if (isset($row['SeatmapID'])) { ?>
      <button class="btn btn-primary" type="submit">Gem som ID
        <?php echo $row['SeatmapID'] ?></button>
      <?php } else { ?>
      <button class="btn btn-primary" type="submit">Gem som nyt Seatmap</button>
      <?php } ?>
    </div>
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
