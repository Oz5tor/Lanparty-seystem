<?php
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }
  switch($action){
    case 'Action':
      $db_conn->query("UPDATE query");
      header("Location: index.php?page=Admin&subpage=Event");
      break;
  }
}

$result = $db_conn->query("
  SELECT
    Event.EventID AS ID,
    Event.Title AS Title,
    Event.Poster AS Poster,
    Event.StartDate AS StartDate,
    Event.EndDate AS EndDate,
    Event.Location AS Location,
    Event.SeatsOpen AS SeatsOpenDate
  FROM
    Event
");
?>
<table class="table table-striped table-condensed table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Event navn</th>
      <th class="text-center" style="width: 10%">Plakat</th>
      <th class="text-center">Åbnings dato</th>
      <th class="text-center">Slut dato</th>
      <th class="text-center">Lokation</th>
      <th class="text-center">Betaling start dato</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td class="text-center"><?php echo $row['ID'] ?></td>
        <td class="text-center"><?php echo $row['Title'] ?></td>
        <td class="text-center"><img class="img-responsive" src="Images/Sponsore/<?php echo $row['Poster'] ?>"></td>
        <td class="text-center"><?php echo date("d M Y - H:i:s", $row['StartDate']); ?></td>
        <td class="text-center"><?php echo date("d M Y - H:i:s", $row['EndDate']); ?></td>
        <td class="text-center"><?php echo $row['Location'] ?></td>
        <td class="text-center"><?php echo date("d M Y - H:i:s", $row['SeatsOpenDate']); ?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
