<table class="table table-striped table-condensed table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Edit</th>
      <th class="text-center">Preview</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td class="text-center"><?php echo $row['UserID'] ?></td>
      <td class="text-center"><?php echo $row['Username'] ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
