<?php
function TorGetUserName($TempUserID, $DBCONN){
  $TempUserID = $DBCONN->real_escape_string($TempUserID);
  $Func_result = $DBCONN->query("SELECT Username from Users Where UserID = '$TempUserID'");
  $Func_row = $Func_result->fetch_assoc();
  return $Func_row['Username'];
}// function end
function GetIDFromUsername($username, $database_connection) {
  $username = $database_connection->real_escape_string($username);
  $result = $database_connection->query("SELECT UserID FROM Users WHERE Username = '$username'")->fetch_assoc();
  return $result['UserID'];
}
?>
