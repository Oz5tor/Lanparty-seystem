<a href="#DoSomethingSomehow" class="btn btn-primary">Opret nyhed</a>
<?php
$result = $db_conn->query( "
    SELECT
      ( SELECT COUNT(*) FROM NEWS ) AS News,
      N.NewsID AS ID,
      N.Title AS Title,
      U1.Username AS Creator,
      U2.Username AS Editor,
      N.CreatedDate AS CreatedDate,
      N.LastEditedDate AS LastEditDate,
      N.Online AS Online
    FROM News N
      INNER JOIN Users U1
        ON N.AuthorID = U1.UserID
      INNER JOIN Users U2
        ON N.LastEditedID = U2.UserID
    LIMIT 20
");
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
      <td><?php echo $row['ID'] ?></td>
      <td><?php echo $row['Title'] ?></td>
      <td><?php echo $row['Creator'] ?></td>
      <td><?php echo $row['Editor'] ?></td>
      <td><?php echo date("d M Y", $row['CreatedDate']); ?></td>
      <td><?php echo date("d M Y", $row['LastEditDate']); ?></td>
      <td><?php echo $row['Online'] ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php
  if ($row['News'] > 20) { ?>
    <ul class="pagination">
      <li <?php if ($p = 1) { echo "class='disable'" } ?>><a href="#">&laquo;</a></li>
      <li <?php if ($p = 1) { echo "class='active'" } ?>>1</li>
      <li><a href="#">2</a></li>
      <li ><a href="#">&raquo;</a></li>
    </ul>
<?php } ?>
