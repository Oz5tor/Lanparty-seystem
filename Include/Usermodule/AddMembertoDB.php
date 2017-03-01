<?php
  $year = date('Y',time());
  $UserID = $_SESSION['UserID'];
  $db_conn->query("INSERT INTO UserMembership (UserID, Year) VALUES ('$UserID', '$year')");
  unset($_SESSION["BuyingMembership"]);
?>