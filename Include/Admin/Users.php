<?php
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }// get id end
    switch($action){
      case 'NonAdmin':
        $db_conn->query("Update Users SET Admin = '0' Where UserID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Users");
        break;
      case 'SetAdmin':
        $db_conn->query("Update Users SET Admin = '1' Where UserID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Users");
        break;
    } // switch end
} // Action end

 // create the Lsit over pages
$result = $db_conn->query("
  SELECT
    Users.UserID,
    Users.Username,
    Users.FullName,
    Users.Created,
    Users.LastLogin,
    Users.Email,
    Users.Admin,
    Users.Phone
  FROM Users
");
?>
<table class="table table-striped table-condensed table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Username</th>
      <th class="text-center">Fulde navn</th>
      <th class="text-center">Opretted</th>
      <th class="text-center">Seneste Login</th>
      <th class="text-center">E-mail</th>
      <th class="text-center">Telefon</th>
      <th class="text-center">Admin</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td class="text-center"><?php echo $row['UserID'] ?></td>
      <td class="text-center"><?php echo $row['Username'] ?></td>
      <td class="text-center"><?php echo $row['FullName']; ?></td>
      <td class="text-center"><?php echo date("d M Y", $row['Created']); ?></td>
      <td class="text-center"><?php echo date("d M Y", $row['LastLogin']); ?></td>
      <td class="text-center"><?php echo $row['Email']; ?></td>
      <td class="text-center"><?php echo $row['Phone']; ?></td>
      <td class="text-center"><?php
          if($row['Admin'] == '1') {
            echo '<a href="?page=Admin&subpage=Users&action=NonAdmin&id='.$row['UserID'].'" alt="Fjern admin" type="button" class="btn btn-success">Admin</a>';
          } else {
            echo '<a href="?page=Admin&subpage=Users&action=SetAdmin&id='.$row['UserID'].'" alt="Set som admin" type="button" class="btn btn-danger">Bruger</a>';
          } ?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
