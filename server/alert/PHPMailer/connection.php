<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'br142.hostgator.com.br ';  // Specify main and backup SMTP servers
$mail->Port='465';
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'teste@systemcall.info';                 // SMTP username
$mail->Password = ',F2IM{WR#s7a';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
$mail->SMTPDebug = 1;

$mail->From = 'teste@systemcall.info';
$mail->FromName = 'Ctracker';

?>
