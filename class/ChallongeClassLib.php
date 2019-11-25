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
    curl_setopt($ch, CURLOPT_URL,'https://oz5tor:dkijyzF3VeQdBxxX713xO6UzofqGbAfjN2jdWHlb@api.challonge.com/v1/'.$what.'.json');
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
  function CreateTournament($att, $api_key){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.challonge.com/v1/tournaments?".$api_key."&".$att,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      #CURLOPT_POSTFIELDS => $postFields
    )); 
    $response = curl_exec($curl);
    $err = curl_error($curl);

    $xmlelement = new SimpleXMLElement($response);
    curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      return $xmlelement;
    }
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
