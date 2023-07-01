<?php
$db_host="localhost"; // Host name
//$db_host="localhost"; // Host name
$db_username="root"; // Mysql username
$db_password=""; // Mysql password
$db_name="lancms_convertdb"; // Database name
$db_conn = new mysqli("$db_host","$db_username","$db_password","$db_name");
$db_conn -> set_charset("utf8");




$result = $db_conn->query("SELECT * FROM newsletters ");

while ($row = $result->fetch_assoc()) {
    $id     = $row["id"];
    $created      = strtotime($row["created_date"]);
    
    
    
    $db_conn->query("Update newsletters SET created_date = '$created' Where id = '$id'");

    
    #$AuthorID = $row["AuthorID"];       
    #$db_conn->query("Update News SET LastEditedID = '$AuthorID' Where NewsID = '$id'");    
    
    
    # news table convert from hlparty.dk 2008 ->

    # User table convert from hlparty.dk 2008 -> LanCMS
    #$id         = $row["id"];
    #$laslogin   = strtotime($row["lastlogin"]);
    #$added      = strtotime($row["added"]);
    #$db_conn->query("Update users SET added = '$added', lastlogin = '$laslogin' WHERE id = '$id'"); 
}
