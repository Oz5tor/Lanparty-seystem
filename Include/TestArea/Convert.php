<?php
$db_host="157.90.34.121"; // Host name
//$db_host="localhost"; // Host name
$db_username="topperto_LanCMS"; // Mysql username
$db_password="o619Jbv4xGq7hP3I3q^322Afp"; // Mysql password
$db_name="topperto_LanCMS"; // Database name
$db_conn = new mysqli("$db_host","$db_username","$db_password","$db_name");
$db_conn -> set_charset("utf8");




$result = $db_conn->query("SELECT * FROM News ");

while ($row = $result->fetch_assoc()) {
    echo $id     = $row["NewsID"].'</br>';
    $created      = $row["CreatedDate"];
    #$laslogin   = strtotime($row["lastlogin"]);
    #$AuthorID = $row["AuthorID"];

    #$db_conn->query("Update News SET LastEditedID = '$AuthorID' Where NewsID = '$id'");    
    $db_conn->query("Update News SET PublishDate = '$created' Where NewsID = '$id'");    
}
