

<?php
// "class/seatmap.php" doesn't work here for some reason.
include_once '../../../class/seatmap.php';
if (!empty($_REQUEST['generated_seatmap'])) {
  $splitedString = preg_split('/[\s\n\r]+/', $_REQUEST['generated_seatmap']);
  $width = strlen($splitedString[0]);
  unset($splitedString);
  $fullString = trim(preg_replace('/\s+/', '', $_REQUEST['generated_seatmap']));
  echo "<h1>Preview</h1>";
} else {
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
  sc.find('A.available').status('unavailable');
  sc.find('c.available').status('unavailable');
  sc.find('s.available').status('unavailable');
  sc.find('n.available').status('unavailable');
  sc.find('k.available').status('unavailable');
});
</script>
