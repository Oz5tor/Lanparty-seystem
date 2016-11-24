<?php
 
// Check if we have received a connection_token
if ( ! empty ($_POST['connection_token']))
{
  echo "Connection token received: ".$_POST['connection_token'];
}
else
{
  echo "No connection token received";
}
?>