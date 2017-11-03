<?php
namespace App\Utils;
use Pure\Utils\Res;
use App\Utils\Mail\PHPMailer;
use Pure\Utils\DynamicHtml;

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

	/**
	 * Envia e-mail de recuperação de senha
	 * para o usuário
	 * @param mixed $name nome do usuário
	 * @param mixed $to e-mail do usuário
	 * @param mixed $title título da mensagem
	 * @param mixed $content link para recuperação de senha
	 * @return boolean foi enviada?
	 */
	public static function send_password_reset($name, $to, $title, $content){
		if(PURE_ENV == 'development') {
			var_dump($content);
			return true;
		}
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
			$mail->MsgHTML('Olá, ' .
				$name .
				'<br><br> <b>Clique <a href="' .
				$content .
				'">aqui</a> para redefinir sua senha. </b><br> Ou copie e cole o link em seu navegador: ' .
				$content .
				'<br><br>Caso não você não tenha solicitado, ignore esse e-mail.');
			$mail->send();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Envia entrada da festa para o cliente
	 * @param mixed $name nome do cliente
	 * @param mixed $to e-mail do cliente
	 * @param mixed $title título do e-mail
	 * @param mixed $content nome QRCode
	 * @return boolean
	 */
	public static function send_ticket($name, $to, $title, $content){
		if(PURE_ENV == 'development') {
			var_dump($content);
			return true;
		}
		$local = BASE_PATH . 'app/assets/images/' . $content . '.png';
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
			$mail->AddReplyTo(SMPT_EMAIL, Res::str('app_title') . ' Não responda');
			$mail->Subject = $title;
			$mail->AddAddress($to, $name);
			$mail->IsHTML(true);
			$mail->AddEmbeddedImage($local, 'ticket_img.png', "ticket_img.png");
			$mail->addAttachment($local, 'ingresso.png');
			$mail->MsgHTML('Olá, ' . $name . '<br><br><br>' .
				'Obrigado por comprar o ingresso para a Festa do TPG!<br>' .
				'Guarde ou imprima esse e-mail e apresente no dia da festa.<br><br>' .
				'Seu ingresso virtual:<br><img src="cid:ticket_img.png"/><br><br>' .
				'Caso o QRCode não apareça, <b>clique <a href="' .
				DynamicHtml::link_to('app/assets/images/' . $content . '.png') .
				'">aqui</a>');
			$mail->send();
			return true;
		}
		catch (Exception $e) {
			return false;
		}
	}

	private static function data_uri($file, $mime)
	{
		$contents = Helpers::url_get_contents($file);
		var_dump($contents);
		$base64   = base64_encode($contents);
		return ('data:' . $mime . ';base64,' . $base64);
	}

	/**
	* Envia relatório de um bug para o administrador
	* @param \Exception $e
	*/
	public static function bug_report($e){
		if(PURE_ENV == 'development') {
			var_dump($e);
			return true;
		}
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
			$mail->Subject = 'Encontramos um bug';
			$mail->AddAddress(SMPT_EMAIL, 'Administrador');
			$mail->MsgHTML('Olá, Administrador<br><br><b> Um bug foi encontrado no sistema. Confira: <br><br> ' .
				$e->getTraceAsString);
			$mail->send();
			return true;
		}
		catch (Exception $e) {
			return false;
		}
	}

}