<?php 
/*
 * Return the user identifier for a user_token received by OneAll. 
 * The goal is to check if there is an existing user account for a user_token received by OneAll.
 */
function get_user_id_for_user_token ($user_token,$tokencoll, $db_conn){
 
    // Example Query: SELECT user_id FROM user_token_link WHERE user_token = <user_token>
    // Return the user_id or null if none found.
    if($result = $db_conn->query("SELECT UserID FROM Users WHERE $tokencoll = '$user_token' AND Inactive = '0'")){
        if($result -> num_rows){
            $row = $result->fetch_assoc();
            return $row['UserID'];
        }else {return null;}
    }
}
//echo get_user_id_for_user_token('45', $db_conn),'<br>';

// ================================================================

/*
 * Return the user_token for a given user identifier.
 * The goal is to check if the given user identifier has already been linked to a OneAll user_token.
 */
function get_user_token_for_user_id ($user_id, $db_conn){
  // Return the user_token or null if none found.  
    if($result = $db_conn->query("SELECT OneallUserToken FROM Users WHERE UserID = '$user_id'")){
        if($result -> num_rows){
            $row = $result->fetch_assoc();
            return $row['OneallUserToken'];
        }else {return null;}
    }
}

//echo get_user_token_for_user_id('1',$db_conn),'<br/>';
// ================================================================

/*
 * Link a user_token to an existing user identifier.
 * The goal is to store a user_token for a given user_id so that we can recognize the user_token lateron.
 */
function link_user_token_to_user_id ($user_token, $user_id,$db_conn){    
    if($result = $db_conn->query("UPDATE Users SET OneallUserToken= '$user_token' WHERE UserID = '$user_id'"))
    {
        if(mysqli_affected_rows($db_conn) == 1){
            return true;
        }else {return false;}
    }
}

//echo link_user_token_to_user_id('48','2', $db_conn);
?>