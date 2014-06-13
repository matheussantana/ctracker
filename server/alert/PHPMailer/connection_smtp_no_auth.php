<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

/*$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup SMTP servers
$mail->Port='465';
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'xxxxxxxxx';                 // SMTP username
$mail->Password = 'yyyyyyyyyyyyyy';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
//$mail->SMTPDebug = 1;

$mail->From = 'yyyyyyyyyyy';
$mail->FromName = 'Ctracker';
*/

//Create a new PHPMailer instance
$mail = new PHPMailer();
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Set the hostname of the mail server
$mail->Host = "stmp.relay.hostname123.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
$mail->SMTPAuth = false;
//Set who the message is to be sent from
$mail->setFrom('no-reply@hostname123.com', 'Ctracker Report');

?>
