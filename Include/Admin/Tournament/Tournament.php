<?php 
//require_once("class/challongeClass.php");

#$site_subdomain = 'hlpf'; // challonges subdomain
#$site_domain = $site_subdomain.'.challonge.com'; // totale site domain
#$site_API_key = 'n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL';
#$resource_uri = 'https://hlpf:'.$site_API_key.'@api.challonge.com/v1/tournaments.json?subdomain=hlpf';
#$json = file_get_contents($resource_uri);
#$data = json_decode($json);
require_once("Include/Admin/Tournament/ChallongeClassLib.php");

$apiKey = "https://hlpf:n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL";
#$what = "tournaments";
#$atributes = "?subdomain=hlpf";
#$data = ChallongeFunctions::ChallongeShowStuff($apiKey, $what ,$atributes);
$what = "tournaments/2927605";
$atributes = "";
$data = ChallongeFunctions::ChallongeShowStuff($apiKey, $what ,$atributes);

echo "<hr>";
echo "<pre>";
$c = 1;

print_r($data->tournament);
/*foreach($data as $i){
  echo $i->tournament->id.'<br>';
  $c++;
}*/
echo "<hr>";
print_r(ChallongeFunctions::CreateTournament("NEWsite Test 016", "HLPArtyNEWsiteTest016", "hlpf", "kage", true, true, 5, ''));
echo "</pre>";
echo $c;
?>