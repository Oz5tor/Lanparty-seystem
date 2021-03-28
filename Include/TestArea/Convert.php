<?php





$result = $db_conn->query("SELECT * FROM News ");

while ($row = $result->fetch_assoc()) {
    echo $id     = $row["NewsID"].'</br>';
    $created      = $row["CreatedDate"];
    #$laslogin   = strtotime($row["lastlogin"]);
    #$AuthorID = $row["AuthorID"];

    #$db_conn->query("Update News SET LastEditedID = '$AuthorID' Where NewsID = '$id'");    
    $db_conn->query("Update News SET PublishDate = '$created' Where NewsID = '$id'");    
}
