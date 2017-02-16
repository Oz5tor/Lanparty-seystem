<?php
$query = "SELECT * FROM Global_Settings";

$result = $db_conn->query($query);

if($result -> num_rows){
  $row = $result->fetch_assoc();
}
