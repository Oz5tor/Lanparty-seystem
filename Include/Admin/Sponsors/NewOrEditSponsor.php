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
  $AllowedFileTypeArray = array('jpg','png','gif');


  if($action == 'Edit'){
    if($_FILES['Banner']['error'] != 4){
      $tempBanner = $_FILES['Banner'];
      $Banner = ImageUploade('Banner','Images/Sponsore',$AllowedFileTypeArray);

      // removal of old image
      $result = $db_conn->query("SELECT Banner FROM Sponsors WHERE SponsorID = '$tempID'");
      $removerow = $result->fetch_assoc();
      unlink('Images/Sponsore/'.$removerow['Banner']);
    }else{
      $Banner = $row['Banner'];
    }
  }
  
  if($action == 'New'){
    if($_FILES['Banner']['error'] != 4){
      $Banner = ImageUploade('Banner','Images/Sponsore',$AllowedFileTypeArray);
    }else{
      $Banner = 'NoBanner.png';
    }
  }

  if($action == 'Edit'){
    // edit Query
    if($db_conn->query("UPDATE Sponsors SET Name = '$Title', Description = '$Context', Url = '$URL',
                                         Online = '$Online', MainSponsor = '$MainSponsor', Banner = '$Banner' WHERE SponsorID = '$tempID'")){
      header("Location: index.php?page=Admin&subpage=Sponsors#admin_menu");
    }
  }else{
    // Create Query
    /* Do stuff with upload banner */
    $LastSortresult = $db_conn->query("SELECT Sort FROM Sponsors ORDER BY Sort DESC LIMIT 1");
    if($LastSortresult->num_rows == 0){
      $LastSortID = 1;
    }else{
      $LastSortRow = $LastSortresult->fetch_assoc();
      $LastSortID = $LastSortRow['Sort']; 
      $LastSortID ++;
    }
    if($db_conn->query("INSERT INTO Sponsors (Name, Description, Url, Online, MainSponsor, Banner, Sort )
                                   VALUES ('$Title', '$Context', '$URL', '$Online', '$MainSponsor', '$Banner', '$LastSortID')")){
     header("Location: index.php?page=Admin&subpage=Sponsors#admin_menu");
    }
  }
}
?>
  <form method="post" action="" enctype="multipart/form-data">
    <div class="form-group col-lg-4">
      <label class="control-lable" for="Title">Title:</label>
      <input required type="text" class="form-control" name="Title" id="Title" value="<?php if(isset($SponsorExist)){echo $row['Name'];} ?>" maxlength="50"/>
    </div>
    <div class="form-group form-inline col-lg-2">
      <label for="Admin">Hovedsponsor: </label>
      <input <?php if(isset($MainSponsor)){echo 'checked';} ?> type="checkbox" name="MainSponsor" id="MainSponsor" value="1"/> &nbsp;&nbsp;
    </div>
    <div class="form-group col-lg-2 col-xs-12">
      <label for="Online">Online:</label>
      <input <?php if(isset($Online)){echo 'checked';} ?> type="checkbox" name="Online" id="Online" value="1"/>
    </div>
    <div class="form-group col-lg-4 col-xs-12">
      <label class="control-lable" for="URL">Link: </label>
      <input class="form-control" type="text" required name="URL" size="40" id="URL" value="<?php if(isset($SponsorExist)){echo $row['Url'];} ?>">
    </div>
    
    <div class="form-group col-lg-2">
      <label for="Banner">Banner: </label>
      <input type="file" name="Banner" id="Banner" value="">
    </div>
    <div class="form-group center-text col-lg-10">
      <?php if($action == 'Edit'){ ?>
      <img class="img-responsive" src="Images/Sponsore/<?php if(isset($SponsorExist)){echo $row['Banner'];} ?>">
      <?php } ?>
    </div>
    <div class="form-group col-lg-12">
      <textarea rows="25" id="AdminTinyMCE" name="AdminTinyMCE"><?php if(isset($SponsorExist)){echo $row['Description'];} ?></textarea>
    </div>
    <div class="form-group text-center col-lg-12">
      <input class="btn btn-default" type="submit" value="Gem" name="Save" />
    </div>
  </form>
