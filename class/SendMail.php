<?php
require_once("PHPMailer/PHPMailerAutoload.php");

$Mailer = new PHPMailer;

$Mailer->isSMTP();
$Mailer->SMTPAuth = true;
$Mailer->SMTPDebug = 2;

$Mailer->Host       = 'smtp.gmail.com';
$Mailer->Username   = 'torsoya@gmail.com';
$Mailer->Password   = ''; // set pass before testing
$Mailer->SMTPSecure = 'ssl';
$Mailer->Port       = 465;

$Mailer->From = 'torsoya@gmail.com';
$Mailer->FromName = 'Tor Soya';
$Mailer->addReplyTo('Kage@kage.dk','Reply adress');
$Mailer->addAddress('age@kage.dk','Tor Soya');

$Mailer->Subject  = "Det her er en email";
$Mailer->Body     = "Kage er godt";
$Mailer->AltBody  = "Kage er godt";

echo "<pre>";
var_dump($Mailer->send());
echo "</pre>";

  
?>