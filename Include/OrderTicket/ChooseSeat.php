<?php
if (!isset($_SESSION['UserID'])) {
  header("Location: index.php");
}


?>

<div class="hlpf_contentbox">
  <div id="Seatmap"></div>
  <div id="Seatmap-Legend"></div>
  <div id="Seatmap-Cart"></div>
</div>
