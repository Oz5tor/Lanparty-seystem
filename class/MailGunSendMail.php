<?php
function MailGunSender($From, $To, $Subject, $HTML, $Key){
    $PlainMail = strip_tags($HTML);
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.mailgun.net/v3/sandbox2bbde681112e4577b97944ad5eae96c2.mailgun.org/messages", # Change this when going to Live
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
// //$text       = "Dette er en test 1..2..3.. <br/> Jeg gik mig en gang over sø og land, <b> der mødte jeg en gamel mand</b>";
// $From       = "tos@hlpf.dk";
// $To         = "torsoya@gmail.com";
// $Subject    = "Dette er en test 1..2..3..4..";
// //echo $PlainMail  = strip_tags($text);
// echo "<br/>";
// $HTML       = '<table align="left" width="50%" border="0" cellspacing="0" cellpadding="0">
// <tbody>
//     <tr>
//         <td align="center" style="background-color:#ffffff">
//             <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #ffffff; border: 3px solid #023a76; border-radius: 10px;">
//                 <tbody>
//                     <tr>
//                         <td style="width:100%">
//                             <table width="640" border="0" cellspacing="0" cellpadding="0" style="background:#d3e7f342;">
//                                 <tbody>
//                                     <tr>
//                                         <td style="width:1200px; "><a href="https://xpay.life/" target="_blank"><img src="http://staging.xpay.life/Content/images/EmailTemplateImages/logo.png" width="" height="105" alt="" style="font-size:0em; margin:0 auto; display:block; border:0px;" class="CToWUd" /></a> </td>                                     
//                                     </tr>                                 
//                                 </tbody>
//                             </table>
//                         </td>
//                     </tr>
//                     <tr>
//                         <td style="width:100%; border-bottom:1px solid #023a76"><img src="" width="10" height="0" alt="" style="display:block;font-size:0em;border:0" class="CToWUd"></td>
//                     </tr>
//                     <tr>
//                         <td>
//                             <table style="font-family: "Montserrat",Arial,Helvetica,sans-serif; font-size: 14px;  padding: 17px; font-weight:500;">
//                                 <tbody>
//                                     <tr>                                        
//                                         <td >
//                                             <p style="">Dear dipanjan mondal,</p>
//                                         </td>
//                                     </tr>
//                                     <tr>
//                                         <td class="date">
//                                             <p style="font-family: "Montserrat",Arial,Helvetica,sans-serif; line-height:1.9;"> Welcome to XPay.Life!</p>                                          
//                                     </tr>
//                                     <tr>
//                                         <td style="padding-top:8px;">                                         
//                                                 <p style="font-family: "Montserrat",Arial,Helvetica,sans-serif; line-height:1.9;"><b>829719</b> is your one time password to proceed on XPay Life. It is valid for 10 minutes. Do not share your OTP with anyone.</p>                                             
//                                         </td>
//                                     </tr>
//                                 </tbody>
//                             </table>
//                         </td>
//                     </tr>
//                     <tr bgcolor="#eaeaea">
//                         <td style="width:100%"><img src="" width="10" height="0" alt="" style="display:block;font-size:0em;border:0px" class="CToWUd"></td>
//                     </tr>                     
//                     <tr>
//                         <td style="width:100%">
//                             <table width="640" border="0" cellspacing="0" cellpadding="0">
//                                 <tbody>
//                                     <tr>
//                                         <td align="left" style="padding-left: 15px; padding-top:15px;">
//                                             <div style="color:#979797;margin-top:0px;letter-spacing:0.016em;font-family: "Montserrat",Arial,Helvetica,sans-serif;">
//                                                 <div style="font-size:15px;color:#000;font-weight:500;font-family: "Montserrat",Arial,Helvetica,sans-serif; padding-bottom: 8px;"><b>For Enquiry?</b></div>
//                                                 <span style="font-size:12px; color:#000; padding-top:2px; line-height: 1.8; font-weight: 600;"><strong>Call Us:</strong> +91-88843 56000 <br><a href="https://xpay.life/" style="color:#000;text-decoration:none" rel="noopener" target="_blank" data-saferedirecturl=""><strong> Email: </strong> info@xpay.life <br /> <strong> Web:  </strong>www.xpay.life</a></strong></span>
//                                             </div>
//                                         </td>
//                                         <td width="150" height="12" alt="" style="display:block;font-size:0em;border:0px;" class="CToWUd"></td>
//                                         <td width="248" style="padding:7px;">
//                                             <table width="171" border="0" cellspacing="0" cellpadding="0">
//                                                 <tbody>
//                                                     <tr>
//                                                         <td align="right" height="30" valign="top" style="font-size:14px;color:#000;font-family: "Montserrat",Arial,Helvetica,sans-serif;letter-spacing:0.01em;font-weight:500">
//                                                             <p style="margin-top:-5px;margin-bottom:10px;float: left;"><b>Stay Connected </b></p>
//                                                         </td>
//                                                     </tr>
//                                                     <tr>
//                                                         <td valign="bottom">
//                                                             <table width="171" border="0" cellspacing="0" cellpadding="0">
//                                                                 <tbody>
//                                                                     <tr>
//                                                                         <td><a href="https://www.facebook.com/XpayLife/?ref=br_rs" rel="noopener" target="_blank" data-saferedirecturl=""> <img src="http://staging.xpay.life/Content/images/EmailTemplateImages/facebook.png" alt="facebook" title="facebook" width="37" height="37" class="CToWUd"></a></td>
//                                                                         <td><a href="https://twitter.com/XpayLife" rel="noopener" target="_blank" data-saferedirecturl=""><img src="http://staging.xpay.life/Content/images/EmailTemplateImages/linkedin.png" alt="Linkedin" title="Linkedin" width="37" height="37" class="CToWUd"></a></td>
//                                                                         <td><a href="https://www.linkedin.com/company/xpay-life/" rel="noopener" target="_blank" data-saferedirecturl=""><img src="http://staging.xpay.life/Content/images/EmailTemplateImages/twitter.png" alt="Twitter" title="Twitter" width="37" height="37" class="CToWUd"></a></td>
//                                                                         <td><a href="https://www.instagram.com/xpay.life/" rel="noopener" target="_blank" data-saferedirecturl=""><img src="http://staging.xpay.life/Content/images/EmailTemplateImages/insta.png" alt="Instagram" title="Instagram" width="37" height="37" class="CToWUd"></a></td>
//                                                                     </tr>
//                                                                 </tbody>
//                                                             </table>
//                                                         </td>
//                                                     </tr>
//                                                 </tbody>
//                                             </table>
//                                         </td>
//                                     </tr>
//                                 </tbody>
//                             </table>
//                         </td>
//                     </tr>
//                     <tr>
//                         <td>
//                             <hr />
//                         </td>
//                     </tr>
//                     <tr>
//                         <td>   
//                             <a href="" target="_blank" style="padding-left:10px; color:#023a76; text-decoration:none; margin-left:5px;"> About Us</a>
//                             <a href="" target="_blank" style="padding-left:10px; color:#023a76; text-decoration:none;">Terms & Condition</a>
//                             <a href="" target="_blank" style="padding-left:10px; color:#023a76; text-decoration:none;"> Privacy Policy</a>
//                         </td>
//                         <td>                    
//                         </td>
//                         <td></td>
//                     </tr>
//                     <tr>                 
//                         <td style="width:100%">
//                             <p style="font-weight: normal;line-height: 1.9;  margin: 16px; font-style: italic; color: #454545; font-size: 12px;"><strong>Important Note:</strong> XPay.Life never asks for your password, bank details or PIN over email or phone. Please do not share your XPay.Life password with anyone. For any query, please write to support@xpay.life  *T & C Apply</p>
//                         </td>
//                     </tr>
//                 </tbody>
//             </table>
//         </td>
//     </tr>
// </tbody>
// </table>';
// $Key        = "YXBpOmYxMWIzNzY1OWQ1NDdlOWU0Nzk1MWIzZTBlYjY3NWQ3LWY4NzdiZDdhLWYzMjBhM2Q2";


// echo MailGunSender($From, $To, $Subject, $HTML, $Key);
?>