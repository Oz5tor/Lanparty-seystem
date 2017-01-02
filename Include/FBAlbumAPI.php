<!--
<?php
/* PHP SDK v5.0.0 */
use Facebook\FacebookRequest;
/* make the API call */
$request = new FacebookRequest(
  Facebook\FacebookApp $app,
  string $accessToken,
  string $method,
  string $endpoint,
  array $params,
  string $eTag,
  string $graphVersion,
  $session,
  'GET',
  '/hlparty/photos?type=uploaded'
);
$response = $request->execute();
$graphObject = $response->getGraphObject();
/* handle the result */
?>
-->
<?php
   $url = "http://graph.facebook.com/125002990909766/albums?fields=id,name&limit=500";
   $obj = json_decode(file_get_contents($url));
   foreach($obj->data as $item) {
    echo "<p>". $item->id . "</p>";
   }
?>