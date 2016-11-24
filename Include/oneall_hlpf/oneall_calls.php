<?php 

//echo phpinfo();

/*
 * Return the user identifier for a user_token received by OneAll. 
 * The goal is to check if there is an existing user account for a user_token received by OneAll.
 */
//require_once("../DBconn.php");

    $db_host="localhost"; // Host name
    $db_username="root"; // Mysql username
    $db_password=""; // Mysql password
    $db_name="oneall_testing"; // Database name
    $db_conn = new mysqli("$db_host","$db_username","$db_password","$db_name");

if ($db_conn->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

function get_user_id_for_user_token ($user_token){
 
    //$Query = "select user_id FROM users WHERE OneallUserToken = '$user_token'";
    
    if($result = $db_conn -> query("SELECT user_id FROM users WHERE OneallUserToken = $user_token", MYSQLI_USE_RESULT)){
        if($result -> num_rows == 1){
            echo '1';
        }else { echo 'none';}
    }
    $result -> close();
    
    // Example Query: SELECT user_id FROM user_token_link WHERE user_token = <user_token>
  // Return the user_id or null if none found.
}

get_user_id_for_user_token("1");

// ================================================================

/*
 * Return the user_token for a given user identifier.
 * The goal is to check if the given user identifier has already been linked to a OneAll user_token.
 */
/*function get_user_token_for_user_id (user_id){
  // Example Query: SELECT user_token FROM user_token_link WHERE user_id = <user_id>
  // Return the user_token or null if none found.
}

// ================================================================

/*
 * Link a user_token to an existing user identifier.
 * The goal is to store a user_token for a given user_id so that we can recognize the user_token lateron.
 */
/*function link_user_token_to_user_id (user_token, user_id){
  // Example: INSERT INTO user_token_link SET user_token = <user_token>, user_id = <user_id>
  // Return true
}*/
?>