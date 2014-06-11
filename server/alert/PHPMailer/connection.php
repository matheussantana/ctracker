<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup SMTP servers
$mail->Port='465';
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'asd2fd23afdv';                 // SMTP username
$mail->Password = 'A@1abcde3';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
//$mail->SMTPDebug = 1;

$mail->From = 'asd2fd23afdv@yahoo.com.br';
$mail->FromName = 'Ctracker';

?>
