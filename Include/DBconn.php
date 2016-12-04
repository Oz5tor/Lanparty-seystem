<?php
    $db_host="localhost"; // Host name
    $db_username="root"; // Mysql username
    $db_password=""; // Mysql password
    $db_name="hlparty2018"; // Database name
    $db_conn = new mysqli("$db_host","$db_username","$db_password","$db_name");
    $db_conn -> set_charset("utf8");
?> 