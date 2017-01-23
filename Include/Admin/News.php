<a href="#DoSomethingSomehow" class="btn btn-primary">Opret nyhed</a>
<?php
function TorGetUserName($TempUserID, $DBCONN){
  $Func_result = $DBCONN->query("SELECT Username from Users Where UserID = '$TempUserID'");
  $Func_row = $Func_result->fetch_assoc();
  return $Func_row['Username'];
}

$result = $db_conn->query( "SELECT * FROM News");
?>
<table class="table table-striped table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Lavet af</th>
      <th>Sidst ændret af</th>
      <th>Lavet den</th>
      <th>Sidst ændret den</th>
      <th>Online</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?php echo $row['NewsID']; ?></td>
      <td><?php echo $row['Title']; ?></td>
      <td><?php echo TorGetUserName($row['AuthorID'], $db_conn); ?></td>
      <td><?php echo TorGetUserName($row['LastEditedID'], $db_conn); ?></td>
      <td><?php echo date("d M Y", $row['CreatedDate']); ?></td>
      <td><?php echo date("d M Y", $row['LastEditedDate']); ?></td>
      <td><?php echo $row['Online']; ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
