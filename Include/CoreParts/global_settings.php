<?php
$query = "SELECT * FROM Global_Settings";

$result = $db_conn->query($query);
while($row = $result->fetch_assoc()) {
  $_GLOBAL[$row['Name']] = $row['Setting'];
}
