<?php 
require_once 'PayPalSDK/autoload.php';
require_once 'Include/CoreParts/DBconn.php';

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

$PaypalAPI = new ApiContext(
  new OAuthTokenCredential(
    'ASWbwU7rVz7cqxLeRK33j4M73jcjnwNpJwvWK0Y2K99oq1-TvC3HZLiYI0YIy6N3zbbYKvrK22wVcggT',
    'EHWPV38yhmpJP5xjeubHM63zwK-0BuY24o_zk8j4LVxsxrhgs5VajIQ_PtOrE1Bh2In3AhEMj3XWEonk'
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
