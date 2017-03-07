<?php 
require_once("Include/Admin/Tournament/ChallongeClassLib.php");

$apiKey = "https://hlpf:n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL";
$what = "tournaments";
$atributes = "";
$data = ChallongeFunctions::ChallongeShowStuff($apiKey, $what ,$atributes);
$what = "tournaments/2927605";
$atributes = "";
$data2 = ChallongeFunctions::ChallongeShowStuff($apiKey, $what ,$atributes);

$what = "tournaments/3288580";
$parms = array(
  "name" => 'name Updated'
);
$data3 = ChallongeFunctions::ChallongeUpdateTournament($apiKey, $what ,$parms);

$data4 = ChallongeFunctions::ChallongeDeleteTournament("https://hlpf:n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL@api.challonge.com/v1/tournaments/3289789.json");

echo "<hr>";
echo "<pre>";

echo "Enkel Tunering<br>";
print_r($data2->tournament);
echo "<hr>";
echo "Tunerings Index ID<br>";
$c = 1;
foreach($data as $i){
  echo $i->tournament->id.' '.$i->tournament->name.' | ';
  $c++;
}
echo 'antal tuneringer i index '.$c;
echo "<hr>";
echo 'tunering updated<br>';
print_r($data3);
echo "<hr>";
echo "Opret tuneringer<br>";
print_r(ChallongeFunctions::CreateTournament("NEWsite Test 011", "HLPArtyNEWsiteTest011", "hlpf", "kage", true, true, 5, ''));
echo "<hr>";
echo 'tunering sletted<br>';
print_r($data4);
echo "</pre>";
?>
