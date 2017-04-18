<?php
// "class/seatmap.php" doesn't work here for some reason.
include_once '../../../class/seatmap.php';
require_once '../../../Include/CoreParts/DBconn.php';
// Check if anything has been send
if (!empty($_REQUEST['generated_seatmap'])) {
// Something has been send, let's do some magic!
  $request = $_REQUEST['generated_seatmap'];
  if (is_numeric($request)) {
    $ID = $db_conn->real_escape_string($_REQUEST['generated_seatmap']);
    $query = "SELECT Width, SeatString
              FROM Seatmap
              WHERE Seatmap.SeatmapID = " . $ID;
    $result = $db_conn->query($query);
    if ($result -> num_rows) {
      $row = $result->fetch_assoc();
      $width = $row['Width'];
      $fullString = $row['SeatString'];
    }
  } else {
    // (Could be updated...) Split every line into an array.
    $splitedString = preg_split('/[\s\n\r]+/', $_REQUEST['generated_seatmap']);
    // Set the width to be the length of the first line.
    $width = strlen($splitedString[0]);
    // Remove all whitespace and newlines.
    $fullString = trim(preg_replace('/\s+/', '', $_REQUEST['generated_seatmap']));
  }
} else {
// If nothing has been send, kill.
  echo "<strong>Error in generation. No map found in \$_REQUEST</strong>";
  exit(0);
}
?>
<h2>Preview</h2>
<div id="map">
  <div id="generated-seat-map"></div>
</div>
<div style="float: left;" id="generated-seat-map-legend"></div>
<script type="text/javascript">
$(document).ready(function() {
  var sc = $('#generated-seat-map').seatCharts({
    map: [<?php seatmap_generation($fullString, $width) ?>],
    seats: {
      A: { classes: 'seatStyle_Arkade' },
      s: { classes: 'seatStyle_Stage' },
      c: { classes: 'seatStyle_Crew' },
      k: { classes: 'seatStyle_Kiosk' }
    },
    legend : {
      node  : $('#generated-seat-map-legend'),
      items : [
        [ 'a', 'available', 'Fri plads' ],
        [ 'c', 'unavailable', 'Crew plads'],
        [ 's', 'unavailable', 'Scene / Storskærm'],
        [ 'A', 'unavailable', 'Arkade'],
        [ 'k', 'unavailable', 'Kiosk'],
        [ 'a', 'unavailable', 'Optaget' ]
      ]
    }
  });
  // Make all these seats unavailable.
  // Unless you want people to buy them, ofcourse.
  sc.find('A.available').status('unavailable');
  sc.find('c.available').status('unavailable');
  sc.find('s.available').status('unavailable');
  sc.find('k.available').status('unavailable');
});
</script>
