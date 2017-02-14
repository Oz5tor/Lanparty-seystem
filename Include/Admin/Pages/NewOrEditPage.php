<?php
if(isset($_GET['id'])){
  $tempID = $db_conn->real_escape_string($_GET['id']);

  if($result = $db_conn->query("Select * From Pages Where PageID = '$tempID'")){
    $row = $result->fetch_assoc();
    if($row['AdminOnly'] == '1'){$adminOnly = true;}
    if($row['Online'] == '1'){$Online = true;}
    $pageExist = true;
  }
}

if(isset($_POST['Save'])){
  $Title    = $db_conn->real_escape_string($_POST['Title']);
  if(isset($_POST['Admin'])) { $Admin    = $db_conn->real_escape_string($_POST['Admin']);}else{$Admin = 0;}
  if(isset($_POST['Online'])){ $Online   = $db_conn->real_escape_string($_POST['Online']);}else{$Online = 0;}
  $Context  = $db_conn->real_escape_string($_POST['AdminTinyMCE']);
  $Time = time();
  $user = $_SESSION['UserID'];
  if($action == 'Edit'){
    // edit Query
    if($db_conn->query("UPDATE Pages SET LastEditedID = '$user', LastEditedDate = '$Time', Content = '$Context', PageTitle = '$Title', Online = '$Online', AdminOnly = '$Admin' WHERE PageID = '$tempID'")){
      header("Location: index.php?page=Admin&subpage=Pages#admin_menu");
    }
  }else{
    // Create Query
    if($db_conn->query("INSERT INTO Pages (AuthorID, LastEditedID,CreatedDate,LastEditedDate,Content,PageTitle,Online,AdminOnly)
                                   VALUES ('$user', '$user', '$Time', '$Time', '$Context', '$Title', '$Online', '$Admin')")){
     header("Location: index.php?page=Admin&subpage=Pages#admin_menu");
    }
  }
}
?>

  <form class="form-group" method="post" action="">
    <div class="form-group col-lg-6">
      <label class="control-label" for="Title">Title:</label>
      <input required class="form-control" type="text" name="Title" id="Title" size="50" value="<?php if(isset($pageExist)){echo $row['PageTitle'];} ?>" maxlength="50"/> &nbsp;&nbsp;
    </div>
    <div class="form-group form-inline col-lg-3">
      <label for="Admin">Admin Side:</label>
      <input <?php if(isset($adminOnly)){echo 'checked';} ?> type="checkbox" name="Admin" id="Admin" value="1"/>
    </div>
    <div class="form-group form-inline col-lg-3">
      <label for="Online">Online:</label>
      <input <?php if(isset($Online)){echo 'checked';} ?> type="checkbox" name="Online" id="Online" value="1"/>
    </div>
    <div class="form-group col-lg-12">
      <textarea rows="25" id="AdminTinyMCE" name="AdminTinyMCE"><?php if(isset($pageExist)){echo $row['Content'];} ?></textarea>
    </div>
    <div class="form-group col-lg-12">
      <input class="btn btn-default" type="submit" value="Gem" name="Save" />
    </div>
  </form>

