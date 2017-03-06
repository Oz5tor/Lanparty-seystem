<?php
class ChallongeFunctions{
  $site_subdomain = 'hlpf'; // challonges subdomain
  $site_domain = $site_subdomain.'.challonge.com'; // totale site domain
  $site_API_key = 'n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL';
  $resource_uri = 'https://hlpf:'.$site_API_key.'@api.challonge.com/v1/tournaments.json';
  
  function ChallongeCurlPost($params){
    $data_json = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $resource_uri);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($ch);
    curl_close($ch);
  }
  
  function ChallongeCurlGet($atributes){
    $json = file_get_contents($resource_uri.''.$atributes);
    $data = json_decode($json);
  }
  
  
  /*true/false*/
  function CreateTournament($TName, $TType, $TUrl, $TDescription, $TSignUpOpen, $TThirdPlaceMatch, $TSignupCap, $TStartTime){
    $params = array(
    );
    
    $TUrl = "http://hlpf.challonge.com/".$TUrl;
    
    ChallongeCurlPost($params);
    
    
    
  }
  
}



?>
