<?php
function sendMail($user, $pass, $to, $sub, $msg)
{
	date_default_timezone_set('Etc/UTC');
	require 'PHPMailerAutoload.php';
	//Idea de Ariel: no tenía los paréntesis, por si 
	//pone la marrana, se los puse...
	$mail = new PHPMailer();

	//setup
	$mail->isSMTP();
	
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465;
	$mail->SMTPSecure = 'ssl';
	$mail->SMTPAuth = true;

	$mail->Username = $user;
	$mail->Password = $pass;
	$mail->addAddress($to);
	$mail->Subject = $sub;
	$mail->msgHTML($msg);
	//res
	if (!$mail->send()) echo "<script>alert('Hubo un error: ".$mail->ErrorInfo."');</script>";
	else echo "<script>alert('Mensaje enviado exitosamente');</script>";
}
?>
