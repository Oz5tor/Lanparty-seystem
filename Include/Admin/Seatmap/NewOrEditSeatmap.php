<?php
if(isset($_GET['id']) && $_GET['id'] != ''){
  $SeatmapID = $db_conn->real_escape_string($_GET['id']);
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
  <h3>Seatmap</h3>
  <textarea id="generate-seat-map" class="hlpf_seatmap-gen" name="generate-seat-map" rows="8" cols="50" autofocus><?php // Keep this tag close to the textarea!
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
    <button class="btn btn-primary" onclick="generatePreview()">Preview</button>
    <button class="btn btn-primary" onclick="saveMe()">Save</button>
</div>
<!-- Find a way to show seatmap here! -->
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
  };

  function saveMe() {
    // On the ID "View-seatmap": load 'page', 'data':'someDataHere'
    $('#View-seatmap')
      .load(
        'Include/Admin/Seatmap/NewOrEditSeatmap.php',
        // Send some data with the loader, called "generated_seatmap".
        // Grab this with $_REQUEST['generated_seatmap']
        { 'generated_seatmap': document.getElementById('generate-seat-map').value }
    )
  };
</script>
