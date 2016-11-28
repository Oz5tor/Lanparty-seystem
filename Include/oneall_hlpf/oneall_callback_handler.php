<?php
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
          $identity_token = $data->user->identity->identity_token;       
   
          // 1) Check if you have a userID for this token in your database
          $user_id = get_user_id_for_user_token($user_token, $db_conn);
   
          // 1a) If the userID is empty then this is the first time that this user 
          // has connected with a social network account on your website
          if ($user_id === null)
          {
            // 1a1) Create a new user account and store it in your database
            // Optionally display a form to collect  more data about the user.
            //$user_id = {The ID of the user that you have created}
              echo $user_token, '<br>';
              echo $data->user->identity->profileUrl,'<br>';
              echo $data->user->identity->birthday,'<br>';
              echo $data->user->identity->name->givenName,'<br>';
              echo $data->user->identity->name->familyName,'<br>';
              echo $data->user->identity->preferredUsername,'<br>';
              echo $data->user->identity->gender,'<br>';
              echo $data->user->identity->emails[0]->value,'<br>';
              
              
              echo "<pre>";
              print_r($data->user);
              echo "</pre>";
            // 1a2) Attach the user_token to the userID of the created account.
            //LinkUserTokenToUserId ($user_token, $user_id);
          }
          // 1b) If you DO have an userID for the user_token then this user has
          // already connected before
          else
          {
              
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
}?>