<?php

require_once("include/CoreParts/DBconn.php");

//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
if (isset($_REQUEST['query'])) {
    $query = $_REQUEST['query'];
    $sql = "SELECT Username FROM Users WHERE Username LIKE '%{$query}%'";
    $result = $db_conn->query($sql);
	$array = array();
    while ($row = mysql_fetch_array($result)) {
        $array[] = array (
            'label' => $row
            'value' => $row,
        );
    }
    //RETURN JSON ARRAY
    echo json_encode ($array);
}

?>