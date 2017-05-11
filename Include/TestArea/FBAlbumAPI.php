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
  return json_decode ($output, true); #array list, and not objec is returned
} // function end


if(!isset($_GET['Album'])){
   # Get all albums
  $url = "https://graph.facebook.com/v2.9/hlparty?fields=albums.limit(15)&access_token=1713545498907250|yPiB5gfHwm-h0ycYq9HH1kCLh9Q"; // %7B = { // %7D = }

  $json = GetFBIMGS($url);

  $ignoreAlbums = array('Timeline Photos','Mobile Uploads','Cover Photos','Untitled Album','Profile Pictures');
  # Sort list and remove unvanted albums
  foreach($json['albums']['data'] as $i => $value){
    if(in_array($json['albums']['data'][$i]['name'],$ignoreAlbums)){
      unset($json['albums']['data'][$i]);
    }
  }
  # Resort to fix key numeric list
  sort($json['albums']['data'], SORT_NUMERIC);
  $albumlist = array();
  for($i = 0; $i < count($json['albums']['data']); $i++){
    $AlId = $json['albums']['data'][$i]['id'];
    $url = 'https://graph.facebook.com/v2.9/'.$AlId.'?fields=cover_photo&access_token=1713545498907250|yPiB5gfHwm-h0ycYq9HH1kCLh9Q';
    $json3 = GetFBIMGS($url); # get album cover img
    # ================================== Collect album info ==================#
    $albumlist[$i] = array(
                        'Name' =>$json['albums']['data'][$i]['name'],
                        'Date' => $json['albums']['data'][$i]['created_time'],
                        'Id' =>$json['albums']['data'][$i]['id'],
                        'Covor' => $json3['cover_photo']['id']
                      );
  }

  echo "<pre>";
  echo 'Der er: '.count($albumlist).' Albummer <br>';
  echo print_r($albumlist[0]);
  echo "</pre>";

  #echo "<pre>";
  #echo print_r($json['albums']['data']);
  #echo "</pre>";

  foreach($albumlist as $album){
    echo '<h3>'.$album['Name'].'</h3>';
    $url = "https://graph.facebook.com/v2.9/".$album['Covor']."?fields=images&access_token=1713545498907250|yPiB5gfHwm-h0ycYq9HH1kCLh9Q"; // %7B = { // %7D = }
    $CovorIMG = GetFBIMGS($url);
    echo '<a href="index.php?page=Gallery&Album='.$album['Id'].'"><img width="200" src="'.$CovorIMG['images'][3]['source'].'"></a>';
  }
  echo '<hr>';
}else{
  $album =  mysqli_real_escape_string($db_conn,strip_tags($_GET['Album']));
  ############################################### Images for album ##############################
  $BollNP = true;
  $imgs = array();
  $cop = 0;
  $c = 1;
  $url = "https://graph.facebook.com/v2.9/".$album."?fields=photos.limit(20)%7Bimages%7D&access_token=1713545498907250|yPiB5gfHwm-h0ycYq9HH1kCLh9Q"; // %7B = { // %7D = }
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

  ######### Print Images to HTML ###########
  foreach($imgs as $i){
    echo '<img width="95" src="'.$i.'">';
  }
  
}// end else

################ Debugging for album images ####################
/*echo "<pre>";
echo print_r($imgs);
echo "</pre>";
echo "<pre>";
echo print_r($json['photos']['paging']['next']);
echo print_r($json['paging']['next']);
echo "</pre>";
echo "<pre>";
echo print_r($json);
echo "</pre>";*/
?>