<h1>Preview</h1>
<?php
if (!empty($_REQUEST['generated_seatmap'])) {
  var_dump($_REQUEST['generated_seatmap']);
  // Full string with no spaces and the width.
  seatmap_generation(string, width);
} else {
  echo "<strong>Error in generation, no map found in \$_REQUEST</strong>";
}
