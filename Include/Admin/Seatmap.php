<?php
  $result = $db_conn->query("SELECT * FROM Seatmap");
?>

<table class="table table-striped table-condensed table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Antal pladser</th>
      <th class="text-center">Crew pladser</th>
      <th class="text-center">Preview</th>
      <th class="text-center">Edit</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td class="text-center"><?php echo $row['SeatmapID'] ?></td>
      <td class="text-center">
      <?php
      echo preg_match_all('/(a)/', $row['SeatString']);
      ?>
      </td>
      <td class="text-center">
      <?php
      echo preg_match_all('/(c)/', $row['SeatString']);
      ?>
      </td>
      <td class="text-center"><button style="width:auto;" class="btn btn-info" onclick="generatePreview(this)" value="<?php echo $row['SeatmapID']?> ">Preview</button></td>
      <td class="text-center"><a style="display:block;" href="?page=Admin&subpage=Seatmap&action=Edit&id='.$row['SeatmapID'].'" alt="Redigér seatmap" type="button" class="btn btn-success">Redigér</a></td>
    </tr>
  <?php } ?>
  </tbody>
</table>

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
