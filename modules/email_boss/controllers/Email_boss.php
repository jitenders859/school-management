<?php
class Email_boss extends Trongate {

	function _send($data) {
		extract($data);
		require 'PHPMailerAutoload.php';

		try {
			//Server settings
			$mail = new PHPMailer;
			$mail->Host = '************'; //<--- change, as required
			$mail->Port = 465; //<--- confirm with your hosting company (usually 465 or 587)
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl'; //<--- confirm with your hosting company (usually 'ssl' or 'tls')
			$mail->Username = '***********'; //usually a mailbox name or an email address
			$mail->Password = '*********'; //<--- the passwords to access your mailbox

			//Recipients
			$mail->setFrom($from_email, $sender_name);
			$mail->addAddress($to_email, $customer_name); //name is optional
			//$mail->addAddress(..) sending of multiple emails is possible

			//Attachments
			//$mail->addAttachment('/path/to/file.zip', 'file.zip');

			//Content
			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $html_msg;
			$mail->AltBody = $plain_text_msg;

			$mail->send();

		} catch(Exception $e) {
			echo 'Message could not be sent: '.$mail->ErrorInfo;
		}

	}

}