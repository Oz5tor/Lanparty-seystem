<?php
session_start();
require_once("oneall_calls.php");
require_once("../DBconn.php");

// Check if we have received a connection_token
if ( ! empty ($_POST['connection_token']))
    {
    // Get connection_token
    $token = $_POST['connection_token'];

    // Your Site Settings
    $site_subdomain = 'hlpartyjoomla';
    $site_public_key = '6058925d-18e5-4c0e-b1ad-39da9dfddbff';
    $site_private_key = 'ffb6dca2-c350-4489-a8b1-f183405c9589';

    // API Access domain
    $site_domain = $site_subdomain.'.api.oneall.com';

    // Connection Endpoint
    // http://docs.oneall.com/api/resources/connections/read-connection-details/
    $resource_uri = 'https://'.$site_domain.'/connections/'.$token .'.json';

    // Setup connection
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $resource_uri);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_USERPWD, $site_public_key . ":" . $site_private_key);
    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
    curl_setopt($curl, CURLOPT_VERBOSE, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_FAILONERROR, 0);

    // Send request
    $result_json = curl_exec($curl);

    // Error
    if ($result_json === false)
    {
    //You may want to implement your custom error handling here
    echo 'Curl error: ' . curl_error($curl). '<br />';
    echo 'Curl info: ' . curl_getinfo($curl). '<br />';
    curl_close($curl);
    }
    // Success
    else
    {
    // Close connection
    curl_close($curl);

    // Decode
    $json = json_decode ($result_json);

    // Extract data
    $data = $json->response->result->data;

    // Check for service
    switch ($data->plugin->key)
    {
        // Social Login
        case 'social_login':

        // Single Sign On
        case 'single_sign_on':

        // Operation successful
        if ($data->plugin->data->status == 'success')
            {
            // The user_token uniquely identifies the user 
            // that has connected with his social network account
            $user_token = $data->user->user_token;
            // The identity_token uniquely identifies the social network account 
            // that the user has used to connect with
            echo $identity_token = $data->user->identity->source->key;
            // 1) Check if you have a userID for this token in your database
            //$user_id = get_user_id_for_user_token($user_token, 'FacebookToken', $db_conn);

            // 1a) If the userID is empty then this is the first time that this user 
            // has connected with a social network account on your website
            switch($identity_token){
                // Battle.net
                case "battlenet":
                  $user_id = get_user_id_for_user_token($user_token, 'BattlenetToken', $db_conn);
                break;
                // Facebook
                case "facebook":
                  $user_id = get_user_id_for_user_token($user_token, 'FacebookToken', $db_conn);
                break;
                // Steam
                case "steam":
                  $user_id = get_user_id_for_user_token($user_token, 'SteamToken', $db_conn);
                break;
                //Google
                case "google":
                  $user_id = get_user_id_for_user_token($user_token, 'GoogleToken', $db_conn);
                break;
                // Twitch TV
                case "twitch":
                  $user_id = get_user_id_for_user_token($user_token, 'TwitchToken', $db_conn);
                break;
            }
                if ($user_id == null)
                {   
                    switch($identity_token){
                        // Battle.net
                        case "battlenet":
                              $_SESSION['SocialNetwork'] = 'battlenet';
                              $_SESSION['UserToken'] = $user_token;
                              $_SESSION['PreffereredUsername'] = $data->user->identity->preferredUsername;  
                              $_SESSION['BattleTag'] = $data->user->identity->accounts[0]->username;
                        break;
                        // Facebook
                        case "facebook":
                              $_SESSION['SocialNetwork'] = 'facebook';
                              $_SESSION['UserToken'] = $user_token;
                              $_SESSION['ProfileUrl'] = $data->user->identity->profileUrl;
                              //$_SESSION['Birthday'] = $data->user->identity->birthday; // no longer requestin birthday from facebook
                              $_SESSION['FullName'] = $data->user->identity->name->formatted;
                              $_SESSION['PreffereredUsername'] = $data->user->identity->preferredUsername;
                              $_SESSION['Email'] = $data->user->identity->emails[0]->value;
                              $_SESSION['PictureUrl'] = $data->user->identity->pictureUrl;                      
                            
                        break;
                        // Steam
                        case "steam":
                              $_SESSION['SocialNetwork'] = 'steam';
                              $_SESSION['UserToken'] = $user_token;
                              $_SESSION['ProfileUrl'] = $data->user->identity->profileUrl;
                              $_SESSION['PreffereredUsername'] = $data->user->identity->preferredUsername;
                              $_SESSION['PictureUrl'] = $data->user->identity->pictureUrl;
                            
                        break; 
                        //Google
                        case "google":
                              $_SESSION['SocialNetwork'] = 'google';
                              $_SESSION['UserToken'] = $user_token;
                              $_SESSION['ProfileUrl'] = $data->user->identity->profileUrl;
                              $_SESSION['FullName'] = $data->user->identity->name->formatted;
                              $_SESSION['Email'] = $data->user->identity->emails[0]->value;
                              $propicture = explode('50',$data->user->identity->pictureUrl);
                              // calling larger picture than what we get from 'pictureUrl'^
                              $_SESSION['PictureUrl'] = $propicture[0].'250';   
                            
                        break;
                        // Twitch TV
                        case "twitch":
                              $_SESSION['SocialNetwork'] = 'twitch';
                              $_SESSION['UserToken'] = $user_token;
                              $_SESSION['ProfileUrl'] = $data->user->identity->profileUrl;
                              $_SESSION['PreffereredUsername'] = $data->user->identity->preferredUsername;
                              $_SESSION['Email'] = $data->user->identity->emails[0]->value;
                              $_SESSION['PictureUrl'] = $data->user->identity->pictureUrl;
                        break;
                    }
                    header("Location: http://localhost/Website-2017/index.php");
                    // 1a1) Create a new user account and store it in your database
                    // Optionally display a form to collect  more data about the user.
                    //$user_id = {The ID of the user that you have created}
                    // 1a2) Attach the user_token to the userID of the created account.
                    //LinkUserTokenToUserId ($user_token, $user_id);
                    /*echo "<pre>";
                    print_r($data->user);
                    echo "</pre>";*/
                    }
                else // if user exxist.
                {
                    $_SESSION['UserID'] = $user_id;
                    if($Result = $db_conn ->query("Select Admin From Users Where UserID = '$user_id'")){
                        $row = $Result->fetch_assoc();
                        $_SESSION['Admin'] = $row['Admin'];
                    }
                     $LastLogin = time();
                     if($db_conn->query("UPDATE Users SET LastLogin = '$LastLogin' WHERE UserID = '$user_id'")){}  
                    header("Location: http://localhost/Website-2017/index.php");
                    // 1b1) The account already exists
                }
                // 2) You have either created a new user or read the details of an existing
                // user from your database. In both cases you should now have a $user_id 

                // 2a) Create a Single Sign On session
                // $sso_session_token = GenerateSSOSessionToken ($user_token, $identity_token); 
                // If you would like to use Single Sign on then you should now call our API
                // to generate a new SSO Session: http://docs.oneall.com/api/resources/sso/

                // 2b) Login this user
                // You now need to login this user, exactly like you would login a user
                // after a traditional (username/password) login (i.e. set cookies, setup 
                // the session) and forward him to another page (i.e. his account dashboard)    
                }
            break;
            }
        }
    }
?>
