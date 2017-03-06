<?php 
//require_once("class/challongeClass.php");

$site_subdomain = 'hlpf'; // challonges subdomain
$site_domain = $site_subdomain.'.challonge.com'; // totale site domain
$site_API_key = 'n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL';
                 //https://username:api-key@api.challonge.com/v1/
$resource_uri = 'https://hlpf:'.$site_API_key.'@api.challonge.com/v1/tournaments.json';
/*
$json = file_get_contents($resource_uri);
$data = json_decode($json);


echo "<hr>";
echo "<pre>";
$c = 1;
foreach($data as $i){
  echo $i->tournament->name.'<br>';
  $c++;
}
//print_r($data);
echo "</pre>";

echo $c;*/
// ======================================================

$params = array(
  "name" => 'NEWsite Test 006',
//  "tournament_type" => '',
  "url" => 'HLPArtyNEWsiteTest006',
  "subdomain" => $site_subdomain.'.challonge.com/HLPArtyNEWsiteTest006',
  "description" => 'Kage test',
  "host" => 35446,
  "open_signup" => true,
  "hold_third_place_match" => true,
  "hide_forum" => true,
  "show_rounds" => true,
  "signup_cap]" => 3
);

$data_json = json_encode($params);
 $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $resource_uri);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
        curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch2, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch2);
        curl_close($ch2);
echo "<pre>";        
print_r($response);
echo "</pre>";

?>