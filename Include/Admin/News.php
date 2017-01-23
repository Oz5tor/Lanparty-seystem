<?php
function TorGetUserName($TempUserID, $DBCONN){
  $Func_result = $DBCONN->query("SELECT Username from Users Where UserID = '$TempUserID'");
  $Func_row = $Func_result->fetch_assoc();
  return $Func_row['Username'];
}

$result = $db_conn->query( "SELECT * FROM News");
?>
<a style="display:block;" href="#DoSomethingSomehow" class="btn btn-info">Opret nyhed</a>
<table class="table table-striped table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Title</th>
      <th class="text-center">Lavet af</th>
      <th class="text-center">Sidst ændret af</th>
      <th class="text-center">Lavet den</th>
      <th class="text-center">Sidst ændret den</th>
      <th class="text-center">Online</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td class="text-center"><?php echo $row['NewsID']; ?></td>
      <td class="text-center"><?php echo $row['Title']; ?></td>
      <td class="text-center"><?php echo TorGetUserName($row['AuthorID'], $db_conn); ?></td>
      <td class="text-center"><?php echo TorGetUserName($row['LastEditedID'], $db_conn); ?></td>
      <td class="text-center"><?php echo date("d M Y", $row['CreatedDate']); ?></td>
      <td class="text-center"><?php echo date("d M Y", $row['LastEditedDate']); ?></td>
      <td class="text-center"><?php echo $row['Online']; ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
