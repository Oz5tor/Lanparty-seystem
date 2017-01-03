<?php

$result = $db_conn->query( "
    SELECT
      Users.UserID, Users.Username, Users.FullName, Users.Address,
      Users.ZipCode, Users.Birthdate, Users.Email, Users.Phone,
      Users.Created, Users.LastLogin, Users.Admin
    FROM Users
");
?>
<table class="table table-striped table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Fulde navn</th>
      <th>Adresse</th>
      <th>Postnr.</th>
      <th>Fødsel.</th>
      <th>Email</th>
      <th>Telefon</th>
      <th>Oprettet</th>
      <th>Last login</th>
      <th>Admin</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?php echo $row['UserID'] ?></td>
      <td><?php echo $row['Username'] ?></td>
      <td><?php echo $row['FullName'] ?></td>
      <td><?php echo $row['Address'] ?></td>
      <td><?php echo $row['ZipCode'] ?></td>
      <td><?php echo $row['Birthdate'] ?></td>
      <td><?php echo $row['Email'] ?></td>
      <td><?php echo $row['Phone'] ?></td>
      <td><?php echo $row['Created'] ?></td>
      <td><?php echo $row['LastLogin'] ?></td>
      <td><?php echo $row['Admin'] ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
