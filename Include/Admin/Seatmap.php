<?php
  if (isset($action) && !empty($action)) {
    include 'Seatmap/NewOrEditSeatmap.php';
  } else {
    $result = $db_conn->query("SELECT * FROM Seatmap");
?>
<a style="display:block;" href="?page=Admin&subpage=Seatmap&action=New" alt="Nyt seatmap"
      type="button" class="btn btn-info">Nyt Seatmap</a>
<hr>
<table class="table table-striped table-condensed table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Navn / Sted</th>
      <th class="text-center">Antal pladser</th>
      <th class="text-center">Crew pladser</th>
      <th class="text-center">Eksempel</th>
      <th class="text-center">Redigér</th>
      <th class="text-center">Slet</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) {
    $disabled = false;
    $eventResult = $db_conn->query("SELECT EndDate FROM Event WHERE Event.Seatmap = " . $row['SeatmapID']);
    if ($eventResult -> num_rows) {

    }
  ?>
    <tr>
      <td class="text-center"><?php echo $row['SeatmapID'] ?></td>
      <td class="text-center"><?php echo $row['Name'] ?></td>
      <td class="text-center"><?php echo $row['Seats']; ?></td>
      <td class="text-center"><?php echo $row['CrewSeats']; ?></td>
      <td class="text-center">
        <button style="width:auto;" class="btn btn-primary"
              onclick="generatePreview(this)"
              value="<?php echo $row['SeatmapID']?>">Se seatmap</button>
      </td>
      <td class="text-center">
        <a style="display:block;" href='?page=Admin&subpage=Seatmap&action=Edit&id=<?php
              echo $row['SeatmapID']?>#admin_menu' alt="Redigér seatmap" type="button"
              class="btn btn-success <?php if ($disabled) { echo "disabled"; }?>">Redigér</a>
      </td>
      <td class="text-center">
        <a style="display:block;" href='?page=Admin&subpage=Seatmap&action=Delete&id=<?php
              echo $row['SeatmapID']?>#admin_menu' alt="Slet seatmap" type="button"
              class="btn btn-danger">Slet</a>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<script src="JS/seat-charts/jquery.seat-charts.min.js"></script>
<div class="col-lg-12" id="View-seatmap"></div>
<script type="text/javascript">
  function generatePreview(objectButton) {
    // On the ID "View-seatmap": load 'page', 'data':'someDataHere'
    $('#View-seatmap')
      .load(
        'Include/Admin/Seatmap/showseatmap.php',
        // Send some data with the loader, called "generated_seatmap".
        // Grab this with $_REQUEST['generated_seatmap']
        {'generated_seatmap': objectButton.value }
    )
  };
</script>
<?php } ?>
