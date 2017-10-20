<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Routes\Route;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Hash;
use Pure\Utils\Res;
use App\Models\User;
use App\Models\Password;
use App\Models\Reset;
use App\Utils\ReCaptcha;
use App\Utils\Helpers;
use App\Utils\Mailer;

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
		$this->render('login/login', 'default');
		exit();
	}

	public function first_access_action(){
		if (Request::is_POST()) {
			$email = $this->params->from_POST('email');
			if (Helpers::email_validation($email)){
				Request::redirect('login/send_reset/' . $email);
			}
			$this->data['error_message'] = 'E-mail inválido.';
		}
		$this->render('login/email');
	}

	public function forgot_action(){
		if (Request::is_POST()) {
			$email = $this->params->from_POST('email');
			if (Helpers::email_validation($email)){
				Request::redirect('login/send_reset/' . $email);
			}
			$this->data['error_message'] = 'E-mail inválido.';
		}
		$this->render('login/email');
	}


	public function send_reset_action($email)
	{
		$user = User::find(['email' => $email]);
		if($user){
			$reset = Reset::find(['user' => $user->id, 'is_activated' => true]);
			if ($reset) {
				if (strtotime($reset->created) > (time() - 1800)){
					$this->data['send'] = true;
					$this->data['message'] =  'Um link para redefinir a senha já foi enviado para o e-mail:';
					$this->data['email'] = $user->email;
					$this->render('login/reset');
					exit();
				} else {
					Reset::update(['is_activated' => '0'])
						->where(['id' => $reset->id])
						->execute();
				}
			}
			$word = Hash::random_word(64);
			$reset = new Reset($word);
			$reset->user = $user->id;
			Reset::save($reset);
			$this->data['send'] = true;
			$this->data['message'] = 'Um link para redefinir a senha foi enviado para o e-mail:';
			$this->data['email'] = $user->email;
			if(PURE_ENV == 'development') {
				echo '<br><br><br>'.DynamicHtml::link_to('login/validate_reset&k=' . $word . '&u=' . $user->id);
			} else {
				Mailer::send_password_reset($user->name, $user->email, Res::str('app_title') . ' - Redefinir a senha', DynamicHtml::link_to('login/validate_reset&k=' . $word . '&u=' . $user->id));
			}
			$this->render('login/reset');
			exit();
		}
		$this->data['message'] = 'Esse e-mail não está cadastrado.';
		$this->render('login/reset');
	}

	public function reset_action()
	{
		$s = $this->session;
		$reset = $s->get('reset');
		if ($reset != false && $reset > (time() - 1800)){
			if(Request::is_POST()){
				$pass = $this->params->unpack('POST', ['re-password','password']);
				if($pass && $pass['re-password'] == $pass['password']) {
					if(strlen($pass['password']) < 6) {
						$this->data['error_message'] = 'A senha necessita ter mais de 6 caracteres.';
						$this->render('login/password');
						exit();
					}
					$password = new Password($pass['password']);
					$id = Password::save($password);
					$user = User::find(['id' => $s->get('reset_id')]);
					User::update(['password' => $id])
						->where(['id' => $user->id])
						->execute();
					$reset = Reset::find(['user' => $s->get('reset_id'), 'is_activated' => true]);
					Reset::update(['is_activated' => '0'])
						->where(['id' => $reset->id])
						->execute();
					$s->wipe('reset');
					$s->wipe('reset_id');
					Auth::authenticate($user->id, $user);
					Request::redirect('site/index');
				}
				$this->data['error_message'] = 'As senhas não conferem.';
			}
			$this->render('login/password');
			exit();
		} else {
			$reset = Reset::find(['user' => $s->get('reset_id'), 'is_activated' => true]);
			if($reset) {
				Reset::update(['is_activated' => '0'])
					->where(['id' => $reset->id])
					->execute();
				$s->wipe('reset');
				$s->wipe('reset_id');
			}
		}
		Request::redirect('site/index');
	}

	public function validate_reset_action()
	{
		$s = $this->session;
		$data = $this->params->unpack('GET', ['k','u']);
		$reset = Reset::find(['user' => $data['u'], 'is_activated' => true]);
		if(isset($data['k']) && $reset) {
			if (strtotime($reset->created) > (time() - 1800)){
				if(Reset::compare($reset, $data['k'])){
					$s->set('reset_id', $reset->user);
					$s->set('reset', time());
					Request::redirect('login/reset');
					exit();
				}
			} else {
				Reset::update(['is_activated' => '0'])
					->where(['id' => $reset->id])
					->execute();
			}
		}
		Request::redirect('error/index');
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
		$user = User::find(['email' => $credential['email'], 'is_activated' => true]);
		if (!$this->validate_captcha()) {
			$this->data['error_message'] = Res::str('captcha');
		}
		else if($user === null) {
			$this->data['error_message'] = Res::str('wrong_email');
		} else if (Password::find(['id' => $user->password]) === null) {
			$this->data['error_message'] = 'Nenhuma senha cadastrada. Clique em "Primeiro acesso"';
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
		if($s->get('require_captcha') /*&& PURE_ENV == 'production'*/) {
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