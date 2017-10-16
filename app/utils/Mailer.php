<?php
namespace App\Utils;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;

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

	public static function send($to, $title, $content){
		$title = Res::str('app_title') . ' - ' . $title;
		$body = '<html><body><table><tr><h1>' . $title. '</h1></tr><tr><td> ' . $content .
			'</td></tr></div></body></html>';
		$headers = 'From: sender@projetox.com'. "\r\n";
		$headers .= 'Reply-To: sender@projetox.com' . "\r\n";
		$headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
		mail($to, $title, $body, $headers);
	}

}