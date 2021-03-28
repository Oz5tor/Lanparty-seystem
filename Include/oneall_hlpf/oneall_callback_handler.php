<?php
ob_start();
session_start();
require_once("oneall_calls.php");
require_once("../CoreParts/DBconn.php");

// Check if we have received a connection_token
if ( ! empty ($_POST['connection_token']))
    {
    // Get connection_token
    $token = $_POST['connection_token'];

    // Your Site Settings
    $site_subdomain = 'topper-tordk';
    $site_public_key = '679f7a14-2083-41d0-9944-9d6b2643ec10';
    $site_private_key = '0a29a223-c956-4504-9a3d-fe3c15c5382c';

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
        
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // Check for service
    switch ($data->plugin->key)
    {

      case "social_link":
        if ($data->plugin->data->status == 'success')
        {
          if (isset($_SESSION['OSUID'])) {
            $OSUID = $_SESSION['OSUID'];
            $temptoken = $data->user->user_token;
            $_SESSION['OneAllToken'] = $data->user->user_token;
            link_user_token_to_user_id($temptoken, $OSUID, $db_conn);
            //unset($_SESSION['OSUID']);
          }else{
            $temptoken = $_SESSION['OneAllToken'];
          }
          //Identity linked
          if ($data->plugin->data->action == 'link_identity'){
            //The identity <identity_token> has been linked to the user <user_token>
            $user_token = $data->user->user_token;
            $identity_token = $data->user->identity->identity_token;
            if($user_token === $_SESSION['OneAllToken']){
              echo "bob";
              $_SESSION['Linked'] = true;
              $ProfileURL = $data->user->identity->profileUrl;
              $identity_token = $data->user->identity->source->key;
              switch($identity_token){
                case "facebook":
                  $ProfileURL = $data->user->identity->displayName;
                  $db_conn->query("Update Users SET FacebookURL = '$ProfileURL' WHERE OneallUserToken = '$temptoken'");
                  break;
                case "battlenet":
                  $ProfileURL = $data->user->identity->accounts[0]->username;
                  $db_conn->query("Update Users SET BattlenetID = '$ProfileURL' WHERE OneallUserToken = '$temptoken'");
                  break;
                case "google":
                  $db_conn->query("Update Users SET GoogleURL = '$ProfileURL' WHERE OneallUserToken = '$temptoken'");
                  break;
                case "twitch":
                  $db_conn->query("Update Users SET TwitchURL = '$ProfileURL' WHERE OneallUserToken = '$temptoken'");
                  break;
                case "steam":
                  $db_conn->query("Update Users SET SteamURL = '$ProfileURL' WHERE OneallUserToken = '$temptoken'");
                  break;
                case "discord":
                  $ProfileURL = $data->user->identity->displayName;    
                  $db_conn->query("Update Users SET DiscordName = '$ProfileURL' WHERE OneallUserToken = '$temptoken'");
                  break;

              }

              if (isset($_SESSION['OSUID'])) {
                $user_id = $_SESSION['OSUID'];
                $_SESSION['UserID'] = $user_id;
                $_SESSION['OneAllToken'] = $user_token;
                if($Result = $db_conn ->query("Select Admin From Users Where UserID = '$user_id'")){
                    $row = $Result->fetch_assoc();
                    $_SESSION['Admin'] = $row['Admin'];
                }

                $LastLogin = time();
                if($db_conn->query("UPDATE Users SET LastLogin = '$LastLogin' WHERE UserID = '$user_id'")){
                  unset($_SESSION['OSUID']);
                  header("Location: ../../index.php");
                }
              }else{
                header("Location: ../../index.php?page=EditMyProfile");
              }
            }else{
             # echo "the Builder";
              $_SESSION['Linked'] = false;
            }
          }/*end of sucessfull link */
          elseif ($data->plugin->data->action == 'unlink_identity')
          {
            $providers = array();
            $providersToArray = (array)($data->user->identities);
            $tempCount = 0;
            foreach($data->user->identities as $key){
              $providers[] = $providersToArray[$tempCount]->provider;
                $tempCount++;
            }
            #echo "<pre>";
            #print_r($providers);
            #print_r($data->user->identities[0]->provider);
            #echo "</pre>";

            if(!in_array('google',$providers)){
                #echo "Google";
              $db_conn->query("UPDATE Users SET GoogleURL = 'NULL' WHERE OneallUserToken = '$temptoken'");
            }
            if(!in_array('facebook',$providers)){
                #echo "Facebook";
              $db_conn->query("UPDATE Users SET FacebookURL = 'NULL' WHERE OneallUserToken = '$temptoken'");
            }
            if(!in_array('battlenet',$providers)){
                #echo "Battlenet";
              $db_conn->query("UPDATE Users SET BattlenetID = 'NULL' WHERE OneallUserToken = '$temptoken'");
            }
            if(!in_array('twitch',$providers)){
                #echo "Twitch";
              $db_conn->query("UPDATE Users SET TwitchURL = 'NULL' WHERE OneallUserToken = '$temptoken'");
            }
            if(!in_array('steam',$providers)){
                #echo "Steam";
              $db_conn->query("UPDATE Users SET SteamURL = 'NULL' WHERE OneallUserToken = '$temptoken'");
            }
            if(!in_array('discord',$providers)){
                #echo "Discord";
              $db_conn->query("UPDATE Users SET DiscordName = 'NULL' WHERE OneallUserToken = '$temptoken'");
            }

            if($urlResult = $db_conn->query("SELECT SteamURL, GoogleURL, FacebookURL, TwitchURL, BattlenetID, DiscordName FROM Users WHERE OneallUserToken = '$temptoken' LIMIT 1")){
              $urlRow = $urlResult->fetch_assoc();  
             #echo $urlRow["SteamURL"];
             #echo #$urlRow["GoogleURL"];
             #echo $urlRow["FacebookURL"];
             #echo $urlRow["TwitchURL"];
             #echo $urlRow["BattlenetID"];
             #echo $urlRow["DiscordName"];
                
              
              if($urlRow["SteamURL"] == 'NULL' &&
                 #$urlRow["GoogleURL"] == 'NULL' &&
                 $urlRow["FacebookURL"] == 'NULL' &&
                 $urlRow["TwitchURL"] == 'NULL' &&
                 $urlRow["BattlenetID"] == 'NULL' &&
                 $urlRow["DiscordName"] == 'NULL'
              ){
                #echo 'der er ikke nogen sociale netværk tilsluttet';
                session_destroy();
               $_SESSION['SQLStatus'] = $db_conn->query("UPDATE Users SET Inactive = '1' WHERE OneallUserToken = '$temptoken'");
              }
            }
            #echo "<pre>";
            #print_r($data);
            #echo "</pre>";
            header("Location: ../../index.php?page=EditMyProfile");
          }
        }
        break;

      // Single Sign On
      case "social_login":
        // Operation successful
        if ($data->plugin->data->status == 'success')
            {
            // The user_token uniquely identifies the user
            // that has connected with his social network account
            $user_token = $data->user->user_token;
            // The identity_token uniquely identifies the social network account
            // that the user has used to connect with
            $identity_token = $data->user->identity->source->key;

            // check of does the user exist in the database vv
            switch($identity_token){
                // Battle.net
                case "battlenet":
                  $user_id = get_user_id_for_user_token($user_token, 'OneallUserToken', $db_conn);
                break;
                // Facebook
                case "facebook":
                  $user_id = get_user_id_for_user_token($user_token, 'OneallUserToken', $db_conn);
                break;
                // Steam
                case "steam":
                  $user_id = get_user_id_for_user_token($user_token, 'OneallUserToken', $db_conn);
                break;
                //Google
                case "google":
                  $user_id = get_user_id_for_user_token($user_token, 'OneallUserToken', $db_conn);
                break;
                // Twitch TV
                case "twitch":
                  $user_id = get_user_id_for_user_token($user_token, 'OneallUserToken', $db_conn);
                break;
                // Discord
                case "discord":
                  $user_id = get_user_id_for_user_token($user_token, 'OneallUserToken', $db_conn);
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
                              $_SESSION['ProfileUrl'] = $ProfileURL = $data->user->identity->displayName;
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
                              $_SESSION['PictureUrl'] = $propicture[0].'100';

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
                        // Discord
                        case "discord":
                              $_SESSION['SocialNetwork'] = 'discord';
                              $_SESSION['UserToken'] = $user_token;
                              $_SESSION['ProfileUrl'] = $data->user->identity->displayName;
                              $_SESSION['PreffereredUsername'] = $data->user->identity->preferredUsername;
                              $_SESSION['Email'] = $data->user->identity->emails[0]->value;
                              $_SESSION['PictureUrl'] = $data->user->identity->pictureUrl;
                        break;
                    }
                    header("Location: ../../index.php");
                    echo "<pre>";
                    print_r($data->user);
                    echo "</pre>";
                }
                else // if user exist.
                {
                    $_SESSION['UserID'] = $user_id;
                    $_SESSION['OneAllToken'] = $user_token;
                    if($Result = $db_conn ->query("Select Admin From Users Where UserID = '$user_id'")){
                        $row = $Result->fetch_assoc();
                        $_SESSION['Admin'] = $row['Admin'];
                    }

                    $LastLogin = time();
                    if($db_conn->query("UPDATE Users SET LastLogin = '$LastLogin' WHERE UserID = '$user_id'")){
                      header("Location: ../../index.php");
                    }

                }
            }
            break;
            }
        }
    }
?>
