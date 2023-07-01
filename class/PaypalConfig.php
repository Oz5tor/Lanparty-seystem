<?php 
require_once 'PayPalSDK/autoload.php';
require_once 'Include/CoreParts/DBconn.php';

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

$PaypalAPI = new ApiContext(
  new OAuthTokenCredential(
    'AeBUx9pLgauQGZOr7IbkiFuozV9Tnyzd6e7VW9vaGb1WoE45H5LApcvhkHMkitFt2RVKnN2lvJjzsEHM', # Client ID
    'EN5hLpumCxzZ6G4hCe-J7eiQ9-IBwVmxFseEK96Q6gKchn_uJbuyk7mbvimp7bxo731MZMTOg82ShyVA'  # Secrect Key
  )
);

$PaypalAPI -> setConfig([
  'mode' => 'sandbox',
  'http.ConnectionTimeOut' => 30,
  'log.logenabled' => false,
  'log.FileName' => '',
  'log.LogLevel' => 'FINE',
  'validation.level' => 'log'
]);

?>
