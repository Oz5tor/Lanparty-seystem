<?php
if(isset($_GET['id'])){
  $tempID = $db_conn->real_escape_string($_GET['id']);
  if($result = $db_conn->query("Select * From Sponsors Where SponsorID = '$tempID'")){
      $row = $result->fetch_assoc();
      if($row['MainSponsor'] == '1'){$MainSponsor = true;}
      if($row['Online'] == '1'){$Online = true;}
      $SponsorExist = true;
  }
}

if(isset($_POST['Save'])){
  $Title    = $db_conn->real_escape_string($_POST['Title']);  
  $Context  = $db_conn->real_escape_string($_POST['AdminTinyMCE']);
  if(isset($_POST['MainSponsor'])) { $MainSponsor = $db_conn->real_escape_string($_POST['MainSponsor']);}else{$MainSponsor = 0;}
  if(isset($_POST['Online'])){ $Online = $db_conn->real_escape_string($_POST['Online']);}else{$Online = 0;}
  $URL  = $db_conn->real_escape_string($_POST['URL']);
  $Page = $db_conn->real_escape_string($_POST['Page']);
  
  
  
  if($action == 'Edit'){
    if(isset($_POST['Baner'])){
      $Banner = $db_conn->real_escape_string($_POST['Banner']);
    }else{
    $Banner = $row['Banner'];
    }
  }else{ 
    if(isset($_POST['Baner'])){
      $Banner = $db_conn->real_escape_string($_POST['Banner']);
    }else{
      $Banner = 'NoBanner.png';
    }
  } 
  
  
  
  if($action == 'Edit'){
    // edit Query
    /* Do stuff with upload banner */
    if($db_conn->query("UPDATE Sponsors SET Name = '$Title', Description = '$Context', Url = '$URL',
                                         Online = '$Online', MainSponsor = '$MainSponsor', Banner = '$Banner' WHERE SponsorID = '$tempID'")){
      header("Location: index.php?page=Admin&subpage=Sponsors");
    }
  }else{
    // Create Query
    /* Do stuff with upload banner */
    $LastSortresult = $db_conn->query("SELECT Sort FROM Sponsors ORDER BY Sort DESC LIMIT 1");
    $LastSortRow = $LastSortresult->fetch_assoc();
    $LastSortID = $LastSortRow['Sort'];
    $LastSortID ++;
    if($db_conn->query("INSERT INTO Sponsors (Name, Description, Url, Online, MainSponsor, Banner, Sort )
                                   VALUES ('$Title', '$Context', '$URL', '$Online', '$MainSponsor', '$Banner', '$LastSortID')")){
     header("Location: index.php?page=Admin&subpage=Sponsors"); 
    }
  }
}
?>
<table class="table hlpf_adminmenu">
  <form method="post" action="" enctype="multipart/form-data">
    <tr>
      <td>
        <label for="Title">Title:</label> <input required type="text" name="Title" id="Title" value="<?php if(isset($SponsorExist)){echo $row['Name'];} ?>" maxlength="50"/>
      </td>
      <td>
        <label for="Admin">Hovedsponsor Side:</label> <input <?php if(isset($MainSponsor)){echo 'checked';} ?> type="checkbox" name="MainSponsor" id="MainSponsor" value="1"/> &nbsp;&nbsp;
        <label for="Online">Online:</label> <input <?php if(isset($Online)){echo 'checked';} ?> type="checkbox" name="Online" id="Online" value="1"/>
      </td>
      <td>
        <label for="URL">Link: </label> <input type="text" name="URL" size="40" id="URL" value="<?php if(isset($SponsorExist)){echo $row['Url'];} ?>">
      </td>
    </tr>
    <tr>
      <td>
        <label for="Banner">Banner: </label><input type="file" name="Banner" id="Banner" value="">
      </td>
      <td colspan="2">
        <?php if($action == 'Edit'){
        ?>
        <img class="image-responsive center-text" src="Images/Sponsore/<?php if(isset($SponsorExist)){echo $row['Banner'];} ?>">
        <?php
        }
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="3">
        <textarea rows="25" id="AdminTinyMCE" name="AdminTinyMCE"><?php if(isset($SponsorExist)){echo $row['Description'];} ?></textarea>
      </td>
    </tr>
    <tr>
      <td colspan="3" class="text-center"><input class="btn btn-default" type="submit" value="Gem" name="Save" /></td>
    </tr>
  </form>
</table>
