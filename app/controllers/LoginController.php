<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Routes\Route;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use App\Models\User;
use App\Models\Password;
use Pure\Utils\Res;
use App\Utils\ReCaptcha;

/**
 * Controller de autenticação de usuário
 * Rotas válidas para autenticação no ambiente de desenvolvimento (development: localhost) e
 * no ambiente de produção.
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class LoginController extends Controller
{

	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/login/do
	 *
	 * Development: realiza o login na base de dados local
	 */
	public function do_action()
	{
		$credential = $this->params->unpack('POST', ['email', 'password']);
		if(Request::is_POST() && $credential)
		{
			if($this->do_login($credential)){
				if(User::is_admin()){
					Request::redirect('admin/index');
					exit();
				} else {
					Request::redirect('seller/index');
					exit();
				}
			}
		}
		$this->data['page_name'] = 'Login';
		$this->render('login', 'default');
		exit();
	}

	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/login/exit
	 *
	 * Development: realiza o logout na base de dados local
	 */
	public function exit_action()
	{
		Auth::revoke();
		Request::redirect('site/index');
	}

	/**
	 * Verifica se o usuário está logado e tentando acessar a página de login
	 */
	public function before()
	{
		$allow = [new Route('login', 'exit')];
		if(Auth::is_authenticated() && !Request::is_to($allow))
		{

			Request::redirect('site/index');
		}
	}

	/**
	 * Realiza o processo de autenticação
	 *
	 * @param mixed $credential ['ufrgs' => integer, 'password' => senha]
	 * @return boolean resposta sobre a autenticacao
	 */
	private function do_login($credential = 0)
	{
		$this->generate_captcha();
		$user = User::find(['email' => $credential['email'], 'is_actived' => true]);
		if (!$this->validate_captcha()) {
			$this->data['error_message'] = Res::str('captcha');
		}
		else if($user === null)
		{
			$this->data['error_message'] = Res::str('wrong_email');
		} else if (!Password::compare(Password::find($user->password), $credential['password']))
		{
			$this->data['error_message'] = Res::str('wrong_password');
			$this->data['old_email'] =  $credential['email'];
		} else {
			Auth::authenticate($user->id, $user);
			return true;
		}
		return false;
	}

	private function generate_captcha(){
		$s = $this->session;
		if($s->contains('captcha') && $s->get('captcha_time') > time() - 1800){
			$s->set('captcha', $s->get('captcha') + 1);
			if($s->get('captcha') > 10) {
				$this->data['show_captcha'] = true;
				$s->set('require_captcha', true);
			}
		} else {
			$s->set('captcha', 1);
			$s->set('captcha_time', time());
			$s->wipe('require_captcha');
		}
	}

	private function validate_captcha() {
		$s = $this->session;
		$p = $this->params;
		if($s->get('require_captcha') && PURE_ENV == 'production') {
			$re_captcha = new ReCaptcha(RECAPTCHA_BACKEND);
			if ($p->from_POST('g-recaptcha-response')) {
				$response = $re_captcha->verifyResponse(
						$p->from_SERVER('REMOTE_ADDR'),
						$p->from_POST('g-recaptcha-response')
					);
				if ($response != null && $response->success) {
					$s->wipe('require_captcha');
					$s->wipe('captcha');
					$s->wipe('captcha_time');
					return true;
				}
			}
			return false;
		}
		return true;
	}
}