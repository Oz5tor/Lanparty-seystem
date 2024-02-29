<?php

# Check for Module Rights
if (!isset($_SESSION["NewsLetter"]) && $_SESSION["NewsLetter"] != 1 ) {
  $_SESSION['MsgForUser'] = "du har ikke adgang til modulet GLHF :P";
  header("Location: index.php?page=Admin");
}

if($action != '') {
  if(isset($_GET['id']) && $_GET['id'] != '') {
    $URLID = $db_conn->real_escape_string($_GET['id']);
  }// get id end
  switch($action) {
    case 'Edit':
      $NewOrEditNewsLetter = true;
      break;
    case 'New':
      $NewOrEditNewsLetter = true;
      break;     
      $Lettercount = $LetterResult->num_rows;
      if($Lettercount == 1) {
        $LetterRow = $LetterResult->fetch_assoc();
        $Title = $LetterRow['Subject'];
        $Body = $LetterRow['Body'];
        $NewsResult = $db_conn->query("SELECT Users.FullName, Users.Email, 
        Users.NewsLetter FROM Users WHERE Users.NewsLetter = 1");
        while($NewsRow = $NewsResult->fetch_assoc()) {
          // Send mail
          #echo SendMail($NewsRow["Email"],$NewsRow["FullName"],$Title,$Body,$_GLOBAL);
          echo "<hr>";
        }// End of Users that want news
        // update news letter to be sent querry
        $sentTime = time();
        $statement = $db_conn->query("UPDATE NewsLetter SET
        SentDate = '$sentTime' WHERE LetterID = '$URLID'");
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
$result = $db_conn->query("SELECT * FROM NewsLetter ORDER BY LetterID DESC");
?>
<a style="display:block;" href="?page=Admin&subpage=NewsLetter&action=New#admin_menu" alt="Nyt Nyhedsbrev" type="button" class="text-center btn btn-info">
  Nyt Nyhedsbrev
</a>
<hr>
<table class="table table-striped table-condensed table-hover LanCMSadminmenu">
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
      <td class="text-center"><?= $row['Subject'] ?></td>
      <td class="text-center"><?= TorGetUserName($row['Author'], $db_conn); ?></td>
      <?php if($row['SentDate'] == '0') { ?>
        <td class="text-center"><span class="btn btn-success">Ikke Udsendt</span></td>
      <?php } else { ?>
        <td class="text-center">
        <?= '<span class="btn disabled btn-danger">'.date('d.m.Y |h:i|',$row['SentDate']).'</span>'; ?>
      </td>
      <?php
      } ?>
      <td class="text-center">
        <?='<a href="?page=Admin&subpage=NewsLetter&action=Template&id='.$row['LetterID'].'#admin_menu" alt="Brug som skabelon Sponsor" type="button" class="btn btn-primary">Brug Som skabelon</a>'; ?>
      </td>
      <td class="text-center">
        <?php
          if($row['SentDate'] != 0) {
            echo '<span class="btn disabled btn-warning">Rediger</span>';
          } else {
            echo '<a href="?page=Admin&subpage=NewsLetter&action=Edit&id='.$row['LetterID'].'#admin_menu" alt="Rediger Sponsor" type="button" class="btn btn-warning">Rediger</a>';
          }
        ?>
      </td>
      <td class="text-center">
        <?='<a href="?page=NewsLetter&id='.$row['LetterID'].'" alt="Rediger Sponsor" target="blank" type="button" class="btn btn-default">Vis</a>'; ?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php
}
?>
