<?php

if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLID = $db_conn->real_escape_string($_GET['id']);
  }// get id end
  
  switch($action){
    case 'Edit':
      $NewOrEditNews = true;
      break;
    case 'New':
      $NewOrEditNews = true;
      break;
  }
}

if(isset($NewOrEditNews) && $NewOrEditNews != false){
  include_once("Include/Admin/News/NewOrEditNews.php");
}else{
?>
<a style="display:block;" href="?page=Admin&subpage=News&action=New#admin_menu" class="btn btn-info">Opret nyhed</a>
<hr>
<table class="table table-striped table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Title</th>
      <th class="text-center">Lavet af</th>
      <th class="text-center">Lavet den</th>
      <th class="text-center">Sidst ændret af</th>
      <th class="text-center">Sidst ændret den</th>
      <th class="text-center">Offenlig</th>
      <th class="text-center">Online</th>
      <th class="text-center">Rediger</th>
    </tr>
  </thead>
  <tbody>
<?php
    $result = $db_conn->query("SELECT * FROM News order by PublishDate DESC");
    while ($row = $result->fetch_assoc()) 
    {
?>
    <tr>
      <td class="text-center">
        <?php echo $row['NewsID']; ?>
      </td>
      <td class="text-center">
        <?php echo $row['Title']; ?>
      </td>
      <td class="text-center">
        <?php echo TorGetUserName($row['AuthorID'], $db_conn); ?>
      </td>
      <td class="text-center">
        <?php echo date("d M Y", $row['CreatedDate']); ?>
      </td>
      <td class="text-center">
        <?php echo TorGetUserName($row['LastEditedID'], $db_conn); ?>
      </td>
      <td class="text-center">
        <?php echo date("d M Y", $row['LastEditedDate']); ?>
      </td>
      <td class="text-center">
        <?php
          if($row['PublishDate'] <= time()){
           echo '<span style="display:block;" class="btn disabled btn-success">'.date("d.M.Y - G:i", $row['PublishDate']).'</span>';
          }else{
            echo '<span style="display:block;" class="btn disabled btn-danger">'.date("d.M.Y - G:i", $row['PublishDate']).'</span>';
          }
        ?>
      </td>
      <td class="text-center">
        <?php
          if($row['Online'] == 0){ echo '<span style="display:block;" class="btn disabled btn-danger">Offline</span';}
          else{ echo '<span style="display:block;" class="btn disabled btn-success">Online</span>';}
        ?>
      </td>
      <td class="text-center">
        <?php 
          if($row["PublishDate"] > time()){
          ?>
          <a href="index.php?page=Admin&subpage=News&action=Edit&id=<?php echo $row['NewsID']; ?>#admin_menu" style="display:block;" class="btn btn-warning">Rediger</a>
          <?php
          }else{
          ?>
          <a href="index.php?page=Admin&subpage=News&action=Edit&id=<?php echo $row['NewsID']; ?>#admin_menu" style="display:block;" class="btn disabled btn-danger">Rediger</a>
          <?php
          }
        ?>
      </td>
    </tr>
<?php 
  } 
?>
  </tbody>
</table>
<?php 
}
?>