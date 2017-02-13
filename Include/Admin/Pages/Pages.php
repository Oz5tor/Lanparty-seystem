<?php
// get the username by ID
function TorGetUserName($TempUserID, $DBCONN){
  $Func_result = $DBCONN->query("Select Username from Users Where UserID = '$TempUserID'");
  $Func_row = $Func_result->fetch_assoc();
  return $Func_row['Username'];
}
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }// get id end
    switch($action){
      case 'Offline':
        $db_conn->query("Update Pages SET Online = '0' Where PageID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Pages");
        break;
      case 'Online':
        $db_conn->query("Update Pages SET Online = '1' Where PageID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Pages");
        break;
      case 'Forall':
        $db_conn->query("Update Pages SET AdminOnly = '0' Where PageID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Pages");
        break;
      case 'Foradmin':
        $db_conn->query("Update Pages SET AdminOnly = '1' Where PageID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Pages");
        break;
      case 'New':
        $NewOrEditPage = true;
        break;
      case 'Edit':
        $NewOrEditPage = true;
        break;
    }// switch end
} // Action end

if(isset($NewOrEditPage) && $NewOrEditPage != false){
  include_once("Include/Admin/Pages/NewOrEditPage.php");
}else{
 // create the Lsit over pages
$result = $db_conn->query("Select * from Pages ORDER BY AdminOnly ASC, Online DESC, PageTitle ASC");
?>
<a style="display:block;" href="?page=Admin&subpage=Pages&action=New" alt="Ny Side" type="button" class="text-center btn btn-info">Opret Ny Side</a>
<hr>
<table class="table table-striped table-condensed table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Title</th>
      <th class="text-center">Forfatter</th>
      <th class="text-center">Opretted</th>
      <th class="text-center">Seneste Editor</th>
      <th class="text-center">Seneste ændring</th>
      <th class="text-center">Online</th>
      <th class="text-center">Aministration</th>
      <th class="text-center">Rediger</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td class="text-center"><?php echo $row['PageID'] ?></td>
      <td class="text-center"><?php echo $row['PageTitle'] ?></td>
      <td class="text-center"><?php echo TorGetUserName($row['AuthorID'], $db_conn); ?></td>
      <td class="text-center"><?php echo date('d.m.Y',$row['CreatedDate']); ?></td>
      <td class="text-center"><?php echo TorGetUserName($row['LastEditedID'], $db_conn); ?></td>
      <td class="text-center"><?php echo date('d.m.Y',$row['LastEditedDate']); ?></td>
      <td class="text-center"><?php
          if($row['Online'] == '1'){
            echo '<a style="display:block;" href="?page=Admin&subpage=Pages&action=Offline&id='.$row['PageID'].'" alt="Set Offline" type="button" class="btn btn-success">Online</a>';
          }else{
            echo '<a style="display:block;" href="?page=Admin&subpage=Pages&action=Online&id='.$row['PageID'].'" alt="Set Online" type="button" class="btn btn-danger">Offline</a>';
          }?>
      </td>
      <td class="text-center"><?php if($row['AdminOnly'] == '1'){
            echo '<a style="display:block;" href="?page=Admin&subpage=Pages&action=Forall&id='.$row['PageID'].'" alt="For Alle" type="button" class="btn btn-success">Ja</a>';
          }else{
            echo '<a style="display:block;" href="?page=Admin&subpage=Pages&action=Foradmin&id='.$row['PageID'].'" alt="For Admin" type="button" class="btn btn-danger">Nej</a>';
          } ?>
      </td>
      <td class="text-center">
        <?php
        echo '<a style="display:block;" href="?page=Admin&subpage=Pages&action=Edit&id='.$row['PageID'].'" alt="Rediger Side" type="button" class="btn btn-warning">Rediger</a>';
        ?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php
}
?>
