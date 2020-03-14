<?php
$query = "SELECT * FROM Global_Settings";

$result = $db_conn->query($query);
while($row = $result->fetch_assoc()) {
  $_GLOBAL[$row['Name']] = $row['Setting'];
}

$now = time();

$query2 = "Select * from Event Where StartDate >= $now LIMIT 1";
$result2 = $db_conn->query($query2);
$row2 = $result2->fetch_assoc();

if(!$result2->num_rows == 0){
    $_GLOBAL['EventID'] = $row2['EventID'];
    $_GLOBAL['EventName'] = $row2['Title'];
}
$_GLOBAL['OneAllActive'] = 1;