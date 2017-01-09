<?php

function GetFBIMGS($URl){
  $ch = curl_init();


curl_setopt($ch, CURLOPT_URL, "$URl");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);

$output = curl_exec($ch);
if($output === FALSE){
  echo "<b>CURL ERROR: </b>". curl_error($ch);
}
curl_close($ch);
return json_decode ($output);
} // function end

$json = GetFBIMGS("https://graph.facebook.com/v2.8/631109893632404?fields=photos%7Blink%2Calbum%7D&access_token=EAAYWdebE0nIBAAzWElWRG3xdgt8ncPzTGMgX2gZB3bc1MEmXtmQ40i0dSm0Imvdgdlbd7opjJc0Kop4EpsopNMm3vThWj7vc5V9wGvt4qxclaO7L5ybuNn8h0bHnFkeXBtHV0no1m74K6O4WW");

$imgs = array();

for($i = 0; $i < count($json->photos->data); $i++){
  $imgs[] .= $json->photos->data[$i]->link;
}
//echo "<pre>";
//echo print_r($json);
//echo "</pre>";
//echo print_r($json);
//echo "<hr>";
echo "<pre>";
echo print_r($imgs);
echo "</pre>";
?>