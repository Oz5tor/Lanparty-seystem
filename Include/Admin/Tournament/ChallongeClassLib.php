<?php
# ================================================
# Author: Tor Soya (Torsoya@gmail.com).
# Created 6.March 2017
# Last edidted: 7.March 2017.
# ================================================
class ChallongeFunctions{
  # ================================================
  // Post/create Curl
  function ChallongeCurlPost($params, $what){
    $data_json = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'https://hlpf:n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL@api.challonge.com/v1/'.$what.'.json');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
  }
  # ================================================
  // Get crul func
  function ChallongeCurlGet($atributes){
    $json = file_get_contents($atributes);
    return $data = json_decode($json);
  }
  # ================================================
  // Update crul func
  function ChallongeCurlPut($url, $params){
    $data_json = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
  }
  # ================================================
  // Delete Tournament func
  function ChallongeDeleteTournament($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: '));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response  = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
  }
  # ================================================
  # $apiKey = https://hlpf:n2aigDz8ofsnCwpHSZqapJzIf84f3C5rS4tYh6iL
  # $what = tournaments
  # $atributes = ?subdomain=hlpf
  function ChallongeShowStuff($apiKey, $what ,$atributes){
    $atributes = $apiKey.'@api.challonge.com/v1/'.$what.'.json/'.$atributes;
    return ChallongeFunctions::ChallongeCurlGet($atributes);
  }
  # ================================================
  function CreateTournament($TName, $TUrl, $TSubdomain, $TDescription, $TSignUpOpen, $TThirdPlaceMatch, $TSignupCap, $TStartTime){
    $params = array(
      "name" => $TName,
      //"tournament_type" => $TType,
      "url" => $TUrl,
      "subdomain" => $TSubdomain,
      "description" => $TDescription,
      "open_signup" => $TSignUpOpen,
      "hold_third_place_match" => $TThirdPlaceMatch,
      "hide_forum" => true,
      "show_rounds" => true,
      "open_signup" => false,
      "allow_participant_match_reporting" => 0,
      "signup_cap" => $TSignupCap
    );    
    return ChallongeFunctions::ChallongeCurlPost($params, "tournaments");
  }
  # ================================================
  /*function SetTournamentCheckIn($TournamentID){
    $what = "tournaments/".$TournamentID.'/process_check_ins.json';
    $params = array(
      "include_participants" => $TName,
    );    
    return ChallongeFunctions::ChallongeCurlPost($params, $what);
  }*/
  # ================================================
  function ChallongeUpdateTournament($apiKey, $what, $params){
    $url = $apiKey.'@api.challonge.com/v1/'.$what.'.json/';
    return ChallongeFunctions::ChallongeCurlPut($url, $params);
  }
}
?>