<?php
class ChallongeFunctions{
  public $site_subdomain = 'hlpf'; // challonges subdomain
  //public $site_domain = $site_subdomain.'.challonge.com'; // totale site domain
  //public $site_API_key = 'n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL';
  //$resource_uri = 'https://hlpf:n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL@api.challonge.com/v1/tournaments.json';
  
  function ChallongeCurlPost($params){
    $data_json = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'https://hlpf:n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL@api.challonge.com/v1/tournaments.json');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
  
  function ChallongeCurlGet($atributes){
    $json = file_get_contents($resource_uri.''.$atributes);
    $data = json_decode($json);
  }
  
  
  /*true/false*/
  function CreateTournament($TName, $TUrl, $TDescription, $TSignUpOpen, $TThirdPlaceMatch, $TSignupCap, $TStartTime){
    $params = array(
      "name" => $TName,
      //"tournament_type" => $TType,
      "url" => $TUrl,
      "description" => $TDescription,
      "open_signup" => $TSignUpOpen,
      "hold_third_place_match" => $TThirdPlaceMatch,
      "hide_forum" => true,
      "show_rounds" => true,
      "signup_cap" => $TSignupCap
    );
    
    //$TUrl = "http://hlpf.challonge.com/".$TUrl;
    
    return ChallongeFunctions::ChallongeCurlPost($params);
  }
  
}



?>
