<?php
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }
  switch($action){
    case 'New':
      $newOrEdit = true;
      break;
    case 'Edit':
      $newOrEdit = true;
      break;
  }
}
if (isset($newOrEdit) && $newOrEdit != '') {
  include_once( "Include/Admin/NewOrEditEvent.php" );
} else {
  $result = $db_conn->query("
    SELECT
      Event.EventID AS ID,
      Event.Title AS Title,
      Event.Poster AS Poster,
      Event.StartDate AS StartDate,
      Event.EndDate AS EndDate,
      Event.Location AS Location
    FROM
      Event
    ORDER BY
      Event.StartDate DESC,
      Event.EventID DESC
  ");
  ?>
  <a style="display:block;" href="?page=Admin&subpage=Event&action=New" alt="Ny Side" type="button" class="text-center btn btn-info">Opret ny event</a>
  <hr>
  <table class="table table-striped table-condensed table-hover hlpf_adminmenu">
    <thead>
      <tr>
        <th class="text-center" style="width: 3%;">Plakat</th>
        <th class="text-center">Event navn</th>
        <th class="text-center">Åbnings dato</th>
        <th class="text-center">Slut dato</th>
        <th class="text-center">Rediger</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td class="text-center"><img class="img-responsive" src="Images/EventPoster/<?php echo $row['Poster'] ?>"></td>
          <td class="text-center"><?php echo $row['Title'] ?></td>
          <td class="text-center"><?php echo date("d M Y - H:i", $row['StartDate']); ?></td>
          <td class="text-center"><?php echo date("d M Y - H:i", $row['EndDate']); ?></td>
          <td class="text-center">
            <a href="index.php?page=Admin&subpage=Event&action=Edit&id=<?php echo $row['ID']; ?>#admin_menu" style="display:block;" class="btn btn-warning">Rediger</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php } ?>
