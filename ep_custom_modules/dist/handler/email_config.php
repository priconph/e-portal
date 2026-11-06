<?php
	$mail = new PHPMailer();
	$mail->isSMTP(true);                                      	// Set mailer to use SMTP
	
	// $mail->SMTPDebug = 2; 
	$mail->Host = 'mail3.pricon.ph';  						// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                              	// Enable SMTP authentication
	$mail->Username = 'issinfoservice@pricon.ph';                 	// SMTP username
	$mail->Password = '6nJWqJWLq5Fvns4jK0v2';                           	// SMTP password
	$mail->SMTPSecure = 'ssl';                            		// Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;
?>