<?php
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }// get id end
    switch($action){
      case 'Offline':
        $db_conn->query("Update Sponsors SET Online = '0' Where SponsorID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Sponsors");
        break;
      case 'Online':
        $db_conn->query("Update Sponsors SET Online = '1' Where SponsorID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Sponsors");
        break;
      case 'NotMainSponsor':
        $db_conn->query("Update Sponsors SET MainSponsor = '0' Where SponsorID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Sponsors");
        break;
      case 'MainSponsor':
        $db_conn->query("Update Sponsors SET MainSponsor = '1' Where SponsorID = '$URLPageID' ");
        header("Location: index.php?page=Admin&subpage=Sponsors");
        break;
      case 'Up':
        // Find Curren Order int
        $result = $db_conn->query("Select Sponsors.`Order` From Sponsors WHERE Sponsors.SponsorID = '$URLPageID'");
        $currentOrderID = $result->fetch_assoc();
        $currentOrderID = $currentOrderID['Order'];
        // Finde lower Order int
        $result = $db_conn->query("Select Sponsors.`Order` From Sponsors WHERE Sponsors.`Order` < '$currentOrderID' ORDER BY Sponsors.`Order` DESC LIMIT 1");
        $newOrder = $result->fetch_assoc();
        if($newOrder['Order'] == 1){
          echo $newOrder = $newOrder['Order'];
        }else{ echo $newOrder = $newOrder['Order'] -1;}
        //$db_conn->query("Update Sponsors SET Sponsors.`Order` = '$newOrder' Where SponsorID = '$URLPageID' ");
        //header("Location: index.php?page=Admin&subpage=Sponsors");
        break;  
      case 'Down':
        // Find Curren Order int
        $result = $db_conn->query("Select Sponsors.`Order` From Sponsors WHERE Sponsors.SponsorID = '$URLPageID'");
        $currentOrderID = $result->fetch_assoc();
        $currentOrderID = $currentOrderID['Order'];
        // Finde Higher Order int
        $result = $db_conn->query("Select Sponsors.`Order` From Sponsors WHERE Sponsors.`Order` > '$currentOrderID' ORDER BY Sponsors.`Order` ASC LIMIT 1 ");
        $newOrder = $result->fetch_assoc();
        echo $newOrder = $newOrder['Order']+1;
        //$db_conn->query("Update Sponsors SET Sponsors.`Order` = '$newOrder' Where SponsorID = '$URLPageID' ");
        //$db_conn->query("Update Sponsors SET Order = '$NewOrder' Where SponsorID = '$URLPageID' ");
        //header("Location: index.php?page=Admin&subpage=Sponsors");
        break;  
      case 'Edit':
        $NewOrEditSponsor = true;
        break;
      case 'New':
        $NewOrEditSponsor = true;
        break;
    }// switch end
} // Action end
if(isset($NewOrEditPage) && $NewOrEditPage != false){
  include_once("Include/Admin/NewOrEditSponsor.php");
}else{
  $result = $db_conn->query("Select * from Sponsors ORDER BY Sponsors.`Order` ASC");
  ?>
  <a href="?page=Admin&subpage=Pages&action=New" alt="Ny Side" type="button" class="text-center btn btn-info">Opret Ny Sponsor</a>
  <hr>
  <table class="table table-striped table-condensed table-hover hlpf_adminmenu">
    <thead>
      <tr>
        <th width="15%" class="text-center">Image</th>
        <th class="text-center">ID</th>
        <th class="text-center">Navn</th>
        <th class="text-center">Link</th>
        <th class="text-center">Side</th>
        <th class="text-center">Hoved Sponsor</th>
        <th class="text-center">Online</th>
        <th class="text-center">Order</th>
        <th class="text-center">Rediger</th>

      </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td class="text-center"><?php echo '<img class="img-responsive" src="Images/Sponsore/'.$row['Banner'].'">'; ?></td>
        <td class="text-center"><?php echo $row['SponsorID']; ?></td>
        <td class="text-center"><?php echo $row['Name']; ?></td>
        <td class="text-center"><?php echo '<a href="'.$row['Url'].'">'.$row['Url'].'</a>'; ?></td>
        <td class="text-center"><?php echo $row['PageID']; ?></td>
        <td class="text-center">
          <?php 
            if($row['MainSponsor'] == '1'){
              echo '<a href="?page=Admin&subpage=Sponsors&action=NotMainSponsor&id='.$row['SponsorID'].'" alt="Set Normal Sponsor" type="button" class="btn btn-success">Ja</a>';
            }else{
              echo '<a href="?page=Admin&subpage=Sponsors&action=MainSponsor&id='.$row['SponsorID'].'" alt="Set Main Sponsor" type="button" class="btn btn-danger">Nej</a>';
            }
          ?>
        </td>
        <td class="text-center">
          <?php 
            if($row['Online'] == '1'){
              echo '<a href="?page=Admin&subpage=Sponsors&action=Offline&id='.$row['SponsorID'].'" alt="Set Offline" type="button" class="btn btn-success">Online</a>';
            }else{
              echo '<a href="?page=Admin&subpage=Sponsors&action=Online&id='.$row['SponsorID'].'" alt="Set Online" type="button" class="btn btn-danger">Offline</a>';
            }
          ?>
        </td>
        <td class="text-center">
          <?php 
           echo '<a href="?page=Admin&subpage=Sponsors&action=Up&id='.$row['SponsorID'].'" class="btn btn-success">&uArr;</a> ';
           echo '<a href="?page=Admin&subpage=Sponsors&action=Down&id='.$row['SponsorID'].'" class="btn btn-danger">&dArr;</a>';
           echo $row['Order'];
          ?>
        </td>
        <td class="text-center">
          <?php
          echo '<a href="?page=Admin&subpage=Sponsors&action=Edit&id='.$row['SponsorID'].'" alt="Rediger Sponsor" type="button" class="btn btn-warning">Rediger</a>';
          ?>
        </td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
<?php 
}
?>
