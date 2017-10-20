<?php
namespace App\Utils;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
use App\Utils\Mail\PHPMailer;

/**
 * Mailer short summary.
 *
 * Mailer description.
 *
 * @version 1.0
 * @author 00271922
 */
class Mailer
{

	public static function send_password_reset($name, $to, $title, $content){
		$mail = new PHPMailer(true);
		try {
			$mail->Host = SMPT_HOST;
			$mail->isSMTP();
			$mail->SMTPAuth = true;
			$mail->Port = 587;
			$mail->Username = SMPT_EMAIL;
			$mail->Password = SMPT_PASSWORD;
			$mail->CharSet = 'UTF-8';
			$mail->SetFrom(SMPT_EMAIL, Res::str('app_title'));
			$mail->AddReplyTo(SMPT_EMAIL, Res::str('app_title') . ' - Administrador');
			$mail->Subject = $title;

			$mail->AddAddress($to, $name);
			//Define o corpo do email
			$mail->MsgHTML('Olá, ' . $name . '<br><br> <b>Clique <a href="' . $content . '">aqui</a> para redefinir sua senha. </b><br> Ou copie e cole o link em seu navegador: ' . $content . '<br><br>Caso não você não tenha solicitado, ignore esse e-mail.');
			
			$mail->send();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

}