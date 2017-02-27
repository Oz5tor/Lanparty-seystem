<?php
require_once("PHPMailer/PHPMailerAutoload.php");

$Mailer = new PHPMailer;

$Mailer->IsSMTP();
$Mailer->SMTPAuth = true;
$Mailer->SMTPDebug = 2;

$Mailer->Host       = 'mail.rosenheim.dk ';
$Mailer->Username   = 'tor@rosenheim.dk';
$Mailer->Password   = 'hlparty123'; // set pass before testing
$Mailer->SMTPSecure = 'ssl';
$Mailer->Port       = 465;

$Mailer->From = 'tor@rosenheim.dk';
$Mailer->FromName = 'Tor Soya';
$Mailer->addReplyTo('torsoya@gmail.com','Reply adress');
$Mailer->addAddress('torsoya@gmail.com','Tor Soya');

$Mailer->Subject  = "Vi tro mail virker";
$Mailer->Body     = "HLPartys nye hjemmeside kan sende mails nu ish";
$Mailer->AltBody  = "HLPartys nye hjemmeside kan sende mails nu ish";

echo "<pre>";
var_dump($Mailer->send());
echo "</pre>";
  
?>