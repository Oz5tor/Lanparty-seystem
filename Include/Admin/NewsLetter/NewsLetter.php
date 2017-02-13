<?php
// get the username by ID
function TorGetUserName($TempUserID, $DBCONN){
  $Func_result = $DBCONN->query("SELECT Username from Users Where UserID = '$TempUserID'");
  $Func_row = $Func_result->fetch_assoc();
  return $Func_row['Username'];
}
if($action != '') {
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLID = $db_conn->real_escape_string($_GET['id']);
  }// get id end
    switch($action){
      case 'Edit':
        $NewOrEditNewsLetter = true;
        break;
      case 'New':
        $NewOrEditNewsLetter = true;
        break;
      case 'Send':
        $LetterResult = $db_conn->query("SELECT * FROM NewsLetter WHERE LetterID = '$URLID'");
        $Lettercount = $LetterResult->num_rows;
        if($Lettercount == 1){
          $LetterRow = $LetterResult->fetch_assoc();
          $Title = $LetterRow['Subject'];
          $Body = $LetterRow['Body'];
          $NewsResult = $db_conn->query("Select Users.FullName, Users.Email, Users.NewsLetter From Users Where Users.NewsLetter = 1");
          while($NewsRow = $NewsResult->fetch_assoc()) {
            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=UTF-8';
            // Additional headers
                                  // Visual name          // recivers email
            $headers[] = 'To: '.$NewsRow['FullName'].' <'.$NewsRow['Email'].'>';
            $headers[] = 'From: HLParty Testin <noreply@hlparty.dk>';
            // Mailed it!
            mail($NewsRow['Email'], $Title, $Body, implode("\r\n", $headers));
          }// End of Users that want news
          // update news letter to be sent querry
          $sentTime = time();
          $statement = $db_conn->query("UPDATE NewsLetter SET SentDate = '$sentTime' WHERE LetterID = '$URLID'");
          header("Location: index.php?page=Admin&subpage=NewsLetter#admin_menu");
        }// letter count end
        break;
      case 'Template':
        $NewOrEditNewsLetter = true;
      break;
    }// switch end
} // Action end

if(isset($NewOrEditNewsLetter) && $NewOrEditNewsLetter != false){
  include_once("Include/Admin/NewsLetter/NewOrEditNewsLetter.php");
}else{
 // create the Lsit over pages
$result = $db_conn->query("Select * from NewsLetter ORDER BY SentDate DESC, LetterID DESC");
?>
<a style="display:block;" href="?page=Admin&subpage=NewsLetter&action=New" alt="Nyt Nyhedsbrev" type="button" class="text-center btn btn-info">
  Nyt Nyhedsbrev
</a>
<hr>
<table class="table table-striped table-condensed table-hover hlpf_adminmenu">
  <thead>
    <tr>
      <th class="text-center">Title</th>
      <th class="text-center">Forfatter</th>
      <th class="text-center">Udsent Dato</th>
      <th class="text-center">Brug Som skabelon</th>
      <th class="text-center">Rediger</th>
      <th class="text-center">Se</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td class="text-center"><?php echo $row['Subject'] ?></td>
      <td class="text-center"><?php echo TorGetUserName($row['Author'], $db_conn); ?></td>
      <?php if($row['SentDate'] == '0'){?>
      <td class="text-center"><a href="?page=Admin&subpage=NewsLetter&action=Send&id=<?php echo $row['LetterID']; ?>" class="btn btn-success"onclick="confirm('Er du sikker på du vil sende nyhedbrevet')" style="display:block;">Udsend</a></td>
      <?php }else{
      ?>
      <td class="text-center">
        <?php echo '<span style="display:block;" class="btn disabled btn-danger">'.date('d.m.Y',$row['SentDate']).'</span>'; ?>
      </td>
      <?php
      } ?>
      <td class="text-center">
        <?php
          echo '<a style="display:block;" href="?page=Admin&subpage=NewsLetter&action=Template&id='.$row['LetterID'].'" alt="Brug som skabelon Sponsor" type="button" class="btn btn-primary">Brug Som skabelon</a>';
        ?>
      </td>
      <td class="text-center">
        <?php
          if($row['SentDate'] != 0){
            echo '<span style="display:block;" class="btn disabled btn-warning">Rediger</span>';
          }else{
           echo '<a style="display:block;" href="?page=Admin&subpage=NewsLetter&action=Edit&id='.$row['LetterID'].'" alt="Rediger Sponsor" type="button" class="btn btn-warning">Rediger</a>';
          }
        ?>
      </td>
      <td class="text-center">
        <?php
          echo '<a style="display:block;" href="?page=NewsLetter&id='.$row['LetterID'].'" alt="Rediger Sponsor" target="blank" type="button" class="btn btn-default">Vis</a>';
        ?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php
}
?>
