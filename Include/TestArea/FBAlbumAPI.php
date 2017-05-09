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
  return json_decode ($output, true);
} // function end

$url = "https://graph.facebook.com/v2.9/1333760126700707?fields=photos%7Bimages%7D&access_token=1713545498907250|yPiB5gfHwm-h0ycYq9HH1kCLh9Q";

// %7B = {
// %7D = }
$BollNP = true;
$imgs = array();
$cop = 0;
$c = 1;
while($BollNP){
  $json = GetFBIMGS($url);  
  if($c == 1){
    for($i = 0; $i < count($json['photos']['data']); $i++){
      $imgs[] .= $json['photos']['data'][$i]['images'][1]['source'];
    }
  }else{
    for($i = 0; $i < count($json['data']); $i++){
      $imgs[] .= $json['data'][$i]['images'][1]['source'];
    }
  }
  if($c == 1){
    if(isset($json['photos']['paging']['next'])){
    $url = $json['photos']['paging']['next'];
    $c = 2;
    }else{$BollNP = false;}
  }else{
    if(isset($json['paging']['next'])){
    $url = $json['paging']['next'];
    }else{$BollNP = false;}
  }
  $cop++;
}

foreach($imgs as $i){
  echo '<img width="100" src="'.$i.'">';
}

//echo "<pre>";
//echo print_r($json);
//echo "</pre>";
//echo print_r($json);
//echo "<hr>";
echo "<pre>";
echo print_r($imgs);
echo "</pre>";
echo "<pre>";
echo print_r($json['photos']['paging']['next']);
echo "</pre>";
echo "<pre>";
echo print_r($json);
echo "</pre>";
echo '<hr>';
?>