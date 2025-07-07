<?php

require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';
require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

	public static function send($to, $subject, $htmlContent)
	{
		$mail = new PHPMailer(true);

		try {
			// Configuration du serveur SMTP
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = $_ENV['GMAIL_ADDRESS'];
			$mail->Password = $_ENV['GMAIL_APP_PASSWORD'];
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;

			// Configuration du contenu
			$mail->setFrom($_ENV['GMAIL_ADDRESS'], 'Camagru');
			$mail->addAddress($to);
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $htmlContent;

			// Envoi
			return $mail->send();
		} catch (Exception $e) {
			return "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
		}
	}
}