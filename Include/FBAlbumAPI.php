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

$json = GetFBIMGS("https://graph.facebook.com/v2.8/125002990909766?fields=photos%7Blink%2Calbum%7D&access_token=EAACEdEose0cBAMYFJfg8ukg9ZBdIwQi9ulcM6i3fnu8VJYwJr1ORr9SG22PljlR060z1srjZC9so1dd6fkwJkZAyZBtQ0bUIMwPGBGfgmPZChBzumBpyD1pwvJBYV67cSA90GLykjNxENpvTik234GHZCD7ZBKRN15HUo55SSZC3rgZDZD");

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