<?php

# Check for Module Rights
if (!isset($_SESSION["SeatMap"]) && $_SESSION["SeatMap"] != 1 ) {
  $_SESSION['MsgForUser'] = "du har ikke adgang til modulet GLHF :P";
  header("Location: index.php?page=Admin");
}

if (isset($action) && !empty($action)) {
  include 'Include/Admin/Seatmap/NewOrEditSeatmap.php';
} else {
  $result = $db_conn->query("SELECT * FROM Seatmap");
?>
<a href="?page=Admin&subpage=Seatmap&action=New" alt="Nyt seatmap" type="button" class="btn btn-info">Nyt Seatmap</a>
<hr>
<table class="table table-striped table-condensed table-hover LanCMSadminmenu">
  <thead>
    <tr>
      <th class="text-center hidden-xs hidden-sm">ID</th>
      <th class="text-center">Navn / Sted</th>
      <th class="text-center">Antal pladser</th>
      <th class="text-center">Crew pladser</th>
      <th class="text-center hidden-xs hidden-sm">Eksempel</th>
      <th class="text-center">Redigér</th>
      <th class="text-center">Slet</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) {
    $eventResult = $db_conn->query("SELECT StartDate FROM Event WHERE Event.Seatmap = " .
                                   $row['SeatmapID'])->fetch_assoc();
    if (!empty($eventResult['StartDate']) && time() > $eventResult['StartDate']) {
      $disabled = true;
    } else { $disabled = false; }
  ?>
    <tr>
      <td class="text-center hidden-xs hidden-sm"><?= $row['SeatmapID'] ?></td>
      <td class="text-center"><?= $row['Name'] ?></td>
      <td class="text-center"><?= $row['Seats']; ?></td>
      <td class="text-center"><?= $row['CrewSeats']; ?></td>
      <td class="text-center hidden-xs hidden-sm">
        <button class="btn btn-primary" onclick="generatePreview(this)" value="<?= $row['SeatmapID']?>">Se seatmap</button>
      </td>
      <td class="text-center">
        <a href='?page=Admin&subpage=Seatmap&action=Edit&id=<?= $row['SeatmapID']?>#admin_menu'
              alt="Redigér seatmap<?php if($disabled) { echo " - Seatmap er i brug til et arrangement og kan derfor ikke regidéres"; } ?>" type="button"
              class="btn btn-success<?php if($disabled) { echo " disabled"; } ?>">Redigér</a>
      </td>
      <td class="text-center">
        <a href='?page=Admin&subpage=Seatmap&action=Delete&id=<?= $row['SeatmapID']?>#admin_menu' alt="Slet seatmap" type="button" class="btn btn-danger">Slet</a>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<script src="JS/seat-charts/jquery.seat-charts.min.js"></script>
<div class="col-lg-12 hidden-xs hidden-sm" id="View-seatmap"></div>
<script type="text/javascript">
  function generatePreview(objectButton) {
    // On the ID "View-seatmap": load 'page', 'data':'someDataHere'
    $('#View-seatmap')
      .load(
        'Include/Admin/Seatmap/showseatmap.php',
        // Send some data with the loader, called "generated_seatmap".
        // Grab this with $_REQUEST['generated_seatmap']
        { 'generated_seatmap': objectButton.value }
    )
  };
</script>
<?php } ?>
