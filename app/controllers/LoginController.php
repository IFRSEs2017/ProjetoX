<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Routes\Route;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use App\Models\User;
use App\Models\Password;
use Pure\Utils\Res;

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
				Request::redirect('site/index');
				exit();
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
		$user = User::find(['email' => $credential['email'], 'is_actived' => true]);
		if($user === null)
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
}