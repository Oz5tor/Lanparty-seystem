<?php
function TorGetUserName($TempUserID, $DBCONN){
  $Func_result = $DBCONN->query("Select Username from Users Where UserID = '$TempUserID'");
  $Func_row = $Func_result->fetch_assoc();
  return $Func_row['Username'];
}

$result = $db_conn->query("Select * from Pages ORDER BY PageTitle ASC, Online DESC, AdminOnly ASC");
?>
<table class="table table-striped table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Forfatter</th>
      <th>Opretted</th>
      <th>Seneste Editor</th>
      <th>Seneste ændring</th>
      <th>Online</th>
      <th>Aministration</th>
      
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?php echo $row['PageID'] ?></td>
      <td><?php echo $row['PageTitle'] ?></td>
      <td><?php echo TorGetUserName($row['AuthorID'], $db_conn); ?></td>
        <td><?php echo date('d.m.Y',$row['CreatedDate']); ?></td>
      <td><?php echo TorGetUserName($row['LastEditedID'], $db_conn); ?></td>
      <td><?php echo date('d.m.Y',$row['LastEditedDate']); ?></td>
      <td><?php echo $row['Online'] ?></td>
      <td><?php if($row['AdminOnly'] == '1'){ echo 'Ja';}else {echo 'Nej';} ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
