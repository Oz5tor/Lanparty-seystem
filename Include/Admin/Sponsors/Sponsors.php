<?php
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLID = $db_conn->real_escape_string($_GET['id']);
  }// get id end
  function Sorter($Opreator,$DBCON,$ID){
        // Find Curren Sort int
        $CurrentResult = $DBCON->query("SELECT SponsorID, Sort FROM Sponsors WHERE SponsorID = '$ID'");
        if($CurrentRow = $CurrentResult->fetch_assoc()){
          $CurrentSposID = $CurrentRow['SponsorID']; // current Sort ID
          $CurrentSort = $CurrentRow['Sort'];
        }
        // Find Swap Sort
        if($Opreator == '+'){
          $FindNext = $CurrentSort +1;
        } else {
          $FindNext = $CurrentSort -1;
        }
        $SwapResult = $DBCON->query("SELECT SponsorID, Sort FROM Sponsors WHERE Sort = '$FindNext'");
        if($SwapRow = $SwapResult->fetch_assoc()){
          $SwapSposID = $SwapRow['SponsorID']; // current Sort ID
          $SwapSort = $SwapRow['Sort'];
        }
        // Update the sort ints
        $DBCON->query("UPDATE Sponsors SET Sort = '$SwapSort' WHERE SponsorID = '$CurrentSposID' ");
        $DBCON->query("UPDATE Sponsors SET Sort = '$CurrentSort' WHERE SponsorID = '$SwapSposID' ");
    return true;
  }// sponsor sort function end
  switch($action){
    case 'Offline': // set sponsor to be ofline
      $db_conn->query("Update Sponsors SET Online = '0' Where SponsorID = '$URLID' ");
      header("Location: index.php?page=Admin&subpage=Sponsors#admin_menu");
      break;
    case 'Online': // set sponsor to be online
      $db_conn->query("Update Sponsors SET Online = '1' Where SponsorID = '$URLID' ");
      header("Location: index.php?page=Admin&subpage=Sponsors#admin_menu");
      break;
    case 'NotMainSponsor': // Sponsor is not mainsponsor
      $db_conn->query("Update Sponsors SET MainSponsor = '0' Where SponsorID = '$URLID' ");
      header("Location: index.php?page=Admin&subpage=Sponsors#admin_menu");
      break;
    case 'MainSponsor': // Sponsor is mainsponsor
      $db_conn->query("Update Sponsors SET MainSponsor = '1' Where SponsorID = '$URLID' ");
      header("Location: index.php?page=Admin&subpage=Sponsors#admin_menu");
      break;

    case 'Up': // Give sponsor Higher Listing
      Sorter('-',$db_conn,$URLID);
      header("Location: index.php?page=Admin&subpage=Sponsors#admin_menu");
      break;

    case 'Down': // Give sponsor Lower Listing
      Sorter('+',$db_conn,$URLID);
      header("Location: index.php?page=Admin&subpage=Sponsors#admin_menu");
      break;
    case 'Edit':
      $NewOrEditSponsor = true;
      break;
    case 'New':
      $NewOrEditSponsor = true;
      break;
  }// switch end
} // Action end
if(isset($NewOrEditSponsor) && $NewOrEditSponsor != false) {
  include_once("Include/Admin/Sponsors/NewOrEditSponsor.php");
} else {
  $result = $db_conn->query("SELECT * FROM Sponsors ORDER BY Online DESC, Sort ASC");
  $NumRows = $result->num_rows;
?>
<a href="?page=Admin&subpage=Sponsors&action=New#admin_menu" alt="Ny Side" type="button" class="text-center btn btn-info">Opret Ny Sponsor</a>
<hr>
<table class="table table-striped table-condensed table-hover LanCMSadminmenu">
  <thead>
    <tr>
      <th width="15%" class="text-center hidden-xs hidden-sm">Image</th>
      <th class="text-center">Navn</th>
      <th class="text-center">Link</th>
      <th class="text-center">Hoved Sponsor</th>
      <th class="text-center">Online</th>
      <th class="text-center">Sort</th>
      <th class="text-center">Rediger</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $Counter = 0;
  // Create List of Sponsors
  while ($row = $result->fetch_assoc()) {
  $Counter++;
  ?>
    <tr>
      <td class="text-center hidden-xs hidden-sm"><?= '<img class="img-responsive" src="Images/Sponsore/'.$row['Banner'].'">'; ?></td>
      <td class="text-center"><?= $row['Name']; ?></td>
      <td class="text-center"><?= '<a href="'.$row['Url'].'">'.$row['Url'].'</a>'; ?></td>
      <td class="text-center">
        <?php
          if($row['MainSponsor'] == '1') { // Is Sponsor Main sponsor or not
            echo '<a href="?page=Admin&subpage=Sponsors&action=NotMainSponsor&id='.$row['SponsorID'].'" alt="Set Normal Sponsor" type="button" class="btn btn-success">Ja</a>';
          } else {
            echo '<a href="?page=Admin&subpage=Sponsors&action=MainSponsor&id='.$row['SponsorID'].'" alt="Set Main Sponsor" type="button" class="btn btn-danger">Nej</a>';
          }
        ?>
      </td>
      <td class="text-center">
        <?php // Is the Sponsor onlinen? Button
          if($row['Online'] == '1') {
            echo '<a href="?page=Admin&subpage=Sponsors&action=Offline&id='.$row['SponsorID'].'" alt="Set Offline" type="button" class="btn btn-success">Online</a>';
          } else {
            echo '<a href="?page=Admin&subpage=Sponsors&action=Online&id='.$row['SponsorID'].'" alt="Set Online" type="button" class="btn btn-danger">Offline</a>';
          }
        ?>
      </td>
      <td class="text-center">
      <?php // Sort Up/Down  buttons
      if($Counter != 1){// arrow up
        if($Counter == $NumRows){
          echo '<a style="display:block;" href="?page=Admin&subpage=Sponsors&action=Up&id='.$row['SponsorID'].'" alt="Sort up" class="btn btn-success">&uArr;</a> ';
        } else {
          echo '<a href="?page=Admin&subpage=Sponsors&action=Up&id='.$row['SponsorID'].'" alt="Sort up" class="btn btn-success">&uArr;</a> ';
        }
      }
      if($Counter != $NumRows){ // arrow down
        if($Counter == 1){
          echo '<a style="display:block;" href="?page=Admin&subpage=Sponsors&action=Down&id='.$row['SponsorID'].'" alt="Sort down" class="btn btn-danger">&dArr;</a>';
        } else {
          echo '<a href="?page=Admin&subpage=Sponsors&action=Down&id='.$row['SponsorID'].'" alt="Sort down" class="btn btn-danger">&dArr;</a>';
        }
      } ?>
      </td>
      <td class="text-center">
      <?= '<a style="display:block;" href="?page=Admin&subpage=Sponsors&action=Edit&id='.$row['SponsorID'].'#admin_menu" alt="Rediger Sponsor" type="button" class="btn btn-warning">Rediger</a>'; ?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php } ?>
