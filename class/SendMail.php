<?php
require_once("PHPMailer/PHPMailerAutoload.php");

function SendMail($to,$toname,$subject,$body,$_GLOBAL){
$Mailer = new PHPMailer;

  // connection methohs
  $Mailer->IsSMTP();
  $Mailer->SMTPAuth = true;
  $Mailer->SMTPDebug = 0;

  // Connections Rules sets
  $Mailer->Host       = 'mail.rosenheim.dk';
  $Mailer->Username   = 'tor@rosenheim.dk';
  $Mailer->Password   = 'hlparty123';
  $Mailer->SMTPSecure = 'ssl';
  $Mailer->Port       = 465;

  // is this a html mail and what charset is used
  $Mailer->isHTML(true);
  $Mailer->CharSet = "UTF-8";
  
  // sender and reciver informations
  $Mailer->From = $_GLOBAL['SendMailFrom'];
  $Mailer->FromName = $_GLOBAL['SendMailFromName'];
  $Mailer->addReplyTo($_GLOBAL['SendMailFromName'],'Reply address');
  $Mailer->addAddress($to,$toname);

  // the acutaly mail
  $Mailer->Subject  = $subject;
  $Mailer->Body     = $body;
  $Mailer->AltBody  = strip_tags($body); // for none html clients
  
  if($Mailer->send()){
    return true;
  }else{
    return false;
  }
}
 //SendMail($to,$toname,$subject,$body,$_GLOBAL);
?>