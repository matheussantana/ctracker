<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup SMTP servers
$mail->Port='465';
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'xxxxxxxxx';                 // SMTP username
$mail->Password = 'yyyyyyyyyyyyyy';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
//$mail->SMTPDebug = 1;

$mail->From = 'yyyyyyyyyyy';
$mail->FromName = 'Ctracker';

?>
