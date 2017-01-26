<?php
if(isset($_GET['id']) && $_GET['id'] != ''){
  $SeatmapID = $db_conn->real_escape_string($_GET['id']);
}
include_once 'class/seatmap.php';
?>
<div class="col-lg-5">
  <!-- Instructions - How to make a seatmap. -->
  <h3>Instruktioner</h3>
  <p>Lav et seatmap ved at skrive <code>a</code>, <code>c</code> og <code>_</code> i boksen.</p>
  <h4>Betegnelser</h4>
  <ul>
    <li><code>a</code> En plads der er tilgængelig</li>
    <li><code>c</code> En plads der kun er til crew</li>
    <li><code>_</code> Fri rum</li>
  </ul>
  <p><small>Note: Alle linjer skal have samme længde! Fyld resten af en linje med <code>_</code> hvis det er nødvendigt.</small></p>
</div>
<div class="form-group col-lg-5">
  <?php
  if (isset($SeatmapID) AND $action == "Edit") {
    echo "<h3>Ændre seatmap her</h3>";
  } else {
    echo "<h3>Lav seatmap her</h3>";
  }
  ?>
  <textarea id="generate-seat-map" name="generate-seat-map" rows="8" cols="50" autofocus><?php // Keep this tag close to the textarea!
    if ($action == "Edit" AND !empty($SeatmapID)) {
      $result = $db_conn->query("SELECT * FROM Seatmap WHERE SeatmapID = $SeatmapID");
      if (!empty($result)) {
        $row = $result->fetch_assoc();
        $SeatString = $row['SeatString'];
        $correction = 0;
        for ($i=$row['Width']; $i < strlen($SeatString); $i += $row['Width']) {
          $SeatString = substr_replace($SeatString, "\n", $i+$correction, 0);
          $correction += 1;
        }
        echo $SeatString;
        unset($correction, $aSeatString, $result, $row); // Quick garbage colletion...
      }
    }
    ?></textarea>
    <br>
    <button class="btn btn-primary" onclick="clickMe()">Generate</button>
</div>
<div id="View-seatmap">

</div>
<script type="text/javascript">
  function clickMe() {
    $('#View-seatmap').load('index.php?page=Admin&subpage=Seatmap #View-seatmap')
  };
</script>
