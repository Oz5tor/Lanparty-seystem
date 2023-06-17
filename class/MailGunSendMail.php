<?php
function MailGunSender($From, $To, $Subject, $HTML, $Key){
    $PlainMail = strip_tags($HTML);
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.eu.mailgun.net/v3/mg.hlparty.dk/messages", # Change this when going to Live
	#CURLOPT_URL => "https://api.mailgun.net/v3/sandbox2bbde681112e4577b97944ad5eae96c2.mailgun.org/messages", # Change this when going to Live
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => array('from' => "$From",'to' => "$To",'subject' => "$Subject",'text' => "$PlainMail",'html' => "$HTML",'o:tracking' => 'no'),
    CURLOPT_HTTPHEADER => array(
        "Authorization: Basic $Key"
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}
#    $text       = "Dette er en test 1..2..3.. <br/> Jeg gik mig en gang over sø og land, <b> der mødte jeg en gamel mand</b>";
#    $From       = $_GLOBAL['SendMailFrom'];
#    $To         = "tors2_@hotmail.com";
#    $Subject    = "Dette er en test 1..2..3..4..";
#    //echo $PlainMail  = strip_tags($text);
#    $HTML       = $text;
#    $Key = $_GLOBAL['MailgunKey'];
#echo MailGunSender($From, $To, $Subject, $HTML, $Key);
?>