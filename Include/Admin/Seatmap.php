<?php
if(isset($_GET['id']) && $_GET['id'] != ''){
  $SeatmapID = $db_conn->real_escape_string($_GET['id']);
} ?>
<div class="col-lg-5">
  <!-- Instructions - How to make a seatmap. -->
  <h3>Instruktioner</h3>
  <p>Lav et seatmap ved at skrive <code>a</code>, <code>c</code> og <code>_</code> i boksen til højre.</p>
  <h4>Betegnelser</h4>
  <ul>
    <li><code>a</code> En plads der er tilgængelig</li>
    <li><code>c</code> En plads der kun er til crew</li>
    <li><code>_</code> Fri rum</li>
  </ul>
  <p><small>Note: Alle linjer skal have samme længde! Fyld resten af en linje med <code>_</code> hvis det er nødvendigt.</small></p>
</div>
<div class="form-group col-lg-5">
  <h3>Lav seatmap her</h3>
  <textarea name="generate-seat-map" rows="8" cols="50"><?php // Keep this tag close to the textarea!
    if (!empty($action)) {
      $result = $db_conn->query("SELECT * FROM Seatmap WHERE SeatmapID = $SeatmapID");
      if (!empty($result)) {
        $row = $result->fetch_assoc();
        $actualSeatString = $row['SeatString'];
        $correction = 0;
        for ($i=$row['Width']; $i < strlen($actualSeatString); $i += $row['Width']) {
          $actualSeatString = substr_replace($actualSeatString, "\n", $i+$correction, 0);
          $correction += 1;
        }
        echo $actualSeatString;
        unset($correction, $actualSeatString, $result, $row); // Quick garbage colletion...
      }
    }
    ?></textarea>
</div>
<div class="col-lg-12">
  <h3>Seatmap</h3>
  <!-- Seatmap will be inserted here -->
  <div id="seat-map"></div>
</div>

<!-- Seat-chart for  -->
<script type="text/javascript" src="submodules/jQuery-Seat-Charts/jquery.seat-charts.min.js"></script>
