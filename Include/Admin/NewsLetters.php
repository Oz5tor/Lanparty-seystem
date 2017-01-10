<?php
// get the username by ID
function TorGetUserName($TempUserID, $DBCONN){
  $Func_result = $DBCONN->query("SELECT Username from Users Where UserID = '$TempUserID'");
  $Func_row = $Func_result->fetch_assoc();
  return $Func_row['Username'];
}
if($action != '') {
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }// get id end
    switch($action){
      case 'Edit':
        $NewOrEditNewsLetter = true;
        break;
      case 'New':
        $NewOrEditNewsLetter = true;
      break;
    }// switch end
} // Action end

if(isset($NewOrEditNewsLetter) && $NewOrEditNewsLetter != false){
  include_once("Include/Admin/NewOrEditNewsLetter.php");
}else{
 // create the Lsit over pages
$result = $db_conn->query("Select * from NewsLetter ORDER BY LetterID DESC");
?>
<a href="?page=Admin&subpage=NewsLetters&action=New" alt="Nyt Nyhedsbrev" type="button" class="text-center btn btn-info">
  Nyt Nyhedsbrev
</a>
<hr>
<table class="table table-striped table-condensed table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">ID</th>
      <th class="text-center">Title</th>
      <th class="text-center">Forfatter</th>
      <th class="text-center">Udsent Dato</th>
      <th class="text-center">Brug Som skabelon</th>
      <th class="text-center">Rediger</th>
      
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td class="text-center"><?php echo $row['LetterID'] ?></td>
      <td class="text-center"><?php echo $row['Subject'] ?></td>
      <td class="text-center"><?php echo TorGetUserName($row['Author'], $db_conn); ?></td>
      <?php if($row['SentDate'] == '0'){echo '<td class="text-center">Udsend</td>';}else{
      ?>
      <td class="text-center"><?php echo date('d.m.Y',$row['SentDate']); ?></td>
      <?php
      } ?>
      <td class="text-center">Brug Som skabelon</td>
      <td class="text-center">Rediger</td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php 
}
?>
