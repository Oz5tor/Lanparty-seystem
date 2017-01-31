<?php
// "class/seatmap.php" doesn't work here for some reason.
include_once '../../../class/seatmap.php';
// Check if anything has been send
if (!empty($_REQUEST['generated_seatmap'])) {
  // Something has been send, let's do some magic!

  // (Could be updated...) Split every line into an array.
  $splitedString = preg_split('/[\s\n\r]+/', $_REQUEST['generated_seatmap']);
  // Set the width to be the length of the first line.
  $width = strlen($splitedString[0]);
  // Garbage collection.
  unset($splitedString);
  // Remove all whitespace and newlines.
  $fullString = trim(preg_replace('/\s+/', '', $_REQUEST['generated_seatmap']));
  // We know that SOMETHING has happen, so let's tell the user about
  // it.
  echo "<h1>Preview</h1>";
} else {
  // If nothing has been send, kill.
  echo "<strong>Error in generation. No map found in \$_REQUEST</strong>";
  exit(0);
}
?>

<div style="float: left" id="map">
  <div id="generated-seat-map"></div>
</div>
<script src="JS/seat-charts/jquery.seat-charts.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  var sc = $('#generated-seat-map').seatCharts({
    map: [<?php seatmap_generation($fullString, $width) ?>],
    seats: {
      A: { classes: 'seatStyle_Arkade' },
      s: { classes: 'seatStyle_Stage' },
      c: { classes: 'seatStyle_Crew' },
      k: { classes: 'seatStyle_Kiosk' },
      n: { classes: 'seatStyle_Nothing' }
    }
  });
});
</script>
