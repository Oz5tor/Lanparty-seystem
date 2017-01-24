<?php 
if(isset($_GET['id']) && $_GET['id'] != ''){
  $ID = $db_conn->real_escape_string($_GET['id']);
}

if(isset($_POST['Save'])){ // is the form submitted
  // gather up the information
    $Title          = $db_conn->real_escape_string($_POST['title']);
    $Content        = $db_conn->real_escape_string($_POST['AdminTinyMCE']);
    $PublishDate    = $db_conn->real_escape_string($_POST['publishdate']);
    echo $PublishDate    = strtotime($PublishDate);
    if(isset($_POST['Online'])){ $Online = 1; }else{ $Online = 0;}
    $LastEidtedID   = $_SESSION['UserID'];
    $LastEditedDate = time();
  
  if($action == 'Edit'){
    // are we editing
    $db_conn->query("UPDATE News SET Title = '$Title', Content = '$Content', 
                                     LastEditedID = '$LastEidtedID', LastEditedDate = '$LastEditedDate',
                                     PublishDate = '$PublishDate', Online = '$Online'
                                 WHERE NewsID = '$ID'");
  }else{
    // this is a new news entry
    $db_conn->query("INSERT INTO News (Title, Content, AuthorID, LastEditedID, CreatedDate, LastEditedDate, PublishDate, Online)
                  Values('$Title', '$Content', '$LastEidtedID', '$LastEidtedID', '$LastEditedDate', '$LastEditedDate',  '$PublishDate', '$Online')");
  }
  header("location: index.php?page=Admin&subpage=News#admin_menu");
}

if($action == 'Edit'){
  $result = $db_conn->query("SELECT * FROM News WHERE NewsID = '$ID'");
  $row = $result->fetch_assoc();
  $OutTitle       = $row['Title'];
  $OutContent     = $row['Content'];
  $OutPuplishDate = date("d-m-Y G:i", $row['PublishDate']);
  if($row['Online'] == '1'){$OutOnline = $row['Online'];}
}
?>
<!-- Missing the form -->
<table class="table hlpf_adminmenu">
  <form method="post" action="">
    <tr>
      <td>
        <label for="Title">Title:</label>
        <input size="50" required type="text" name="title" id="Title" value="<?php if(isset($OutTitle)){ echo $OutTitle;} ?>" maxlength="50"/>
      </td>
      <td>
        <label>Offenligørelses dato:</label>
        <input type="datetime" data-date="12-02-2012 23:59" data-date-format="dd-mm-yyyy hh:ii" class="form_datetime" name="publishdate" value="<?php if(isset($OutPuplishDate)){echo $OutPuplishDate;} ?>">
        <span class="add-on"><i class="icon-remove"></i></span>
        <span class="add-on"><i class="icon-th"></i></span>
      </td>
      <td>
        <label>Online</label>
        <input type="checkbox" name="Online" <?php if(isset($OutOnline)){echo 'checked';} ?> value="1">
      </td>
    </tr>
    <tr>
      <td colspan="3">
        <textarea rows="25" id="AdminTinyMCE" name="AdminTinyMCE"><?php if(isset($OutContent)){echo $OutContent;} ?></textarea>
      </td>
    </tr>
    <tr>
      <td colspan="3" class="text-center"><input class="btn btn-default" type="submit" value="Gem" name="Save" /></td>
    </tr>
  </form>
</table>
