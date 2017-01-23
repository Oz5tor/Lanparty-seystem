<?php 
if(isset($_GET['id']) && $_GET['id'] != ''){
  $ID = $db_conn->real_escape_string($_GET['id']);
}

if($_POST['Save']){ // is the form submitted
  // gather up the information
    $Title          = $db_conn->real_escape_string($_POST['title']);
    $Content        = $db_conn->real_escape_string($_POST['content']);
    $PublishDate    = $db_conn->real_escape_string($_POST['publishdate']);
    $LastEidtedID   = $_SESSION['UserId'];
    $LastEditedDate = time();
  
  if($action == 'Edit'){
    // are we editing
    $db_conn->query("UPDATE News SET Title = '$Title', Content = '$Content', 
                                     LastEditedID = '$LastEidtedID', LastEditedDate = '$LastEditedDate', PublishDate = '$PublishDate'
                                 WHERE NewsID = '$ID'");
  }else{
    // this is a new news entry
    $db_conn->query("INSERT INTO News (Title, Content, AuthorID, LastEditedID, CreatedDate, LastEditedDate, PublishDate)
                  Values('$Title','$Content','$LastEidtedID','$LastEditedDate','$LastEditedDate','$LastEditedDate', '$PublishDate')");
  }
}

if($action == 'Edit'){
  $result = $db_conn->query("SELECT * FROM News WHERE NewsID = '$ID'");
  $row = $result->fetch_assoc();
  $OutTitle       = $row['Title'];
  $OutContent     = $row['Content'];
  $OutPuplishDate = $row['PublishDate'];
  
}
?>

<!-- Missing the form -->