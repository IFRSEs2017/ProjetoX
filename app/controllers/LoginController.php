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
use Pure\Db\Database;

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
	 * POST: realiza o login no sistema e redirecionada para a página do usuário
	 * GET: mostra formulário de login
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

	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/login/first_access
	 *
	 * @see public function forgot_action()
	 *
	 * POST: realiza requisição de criação de senha
	 * GET: carrega formulário para redefinir a senha
	 */
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

	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/login/forgot
	 *
	 * @see public function first_access_action()
	 *
	 * POST: realiza requisição de alteração de senha
	 * GET: carrega formulário para redefinição de senha
	 */
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

	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/login/send_reset
	 *
	 * Realiza as verificações necessárias no processo de envio de
	 * pedido de alteração de senha.
	 * Após verificar, envia e-mail com o link para o usuário alterar a senha
	 *
	 * @param string $email email do solicitante
	 */
	public function send_reset_action($email)
	{
		$user = User::find(['email' => $email]);
		// Existe um usuário cadastrado com esse e-mail
		if($user){
			$reset = Reset::find(['user' => $user->id, 'is_activated' => true]);
			// Verifica se já existe pedido de recuperação de senha
			if ($reset) {
				// Verifica se o pedido de recuperação de senha existente
				// não está expirado.
				// Caso o pedido de recuperação ainda esteja válido,
				// carrega a página para o usuário informando
				// que já existe um e-maila tivo
				if (strtotime($reset->created) > (time() - 1800)){
					$this->data['send'] = true;
					$this->data['message'] =  'Um link para redefinir a senha já foi enviado para o e-mail:';
					$this->data['email'] = $user->email;
					$this->render('login/reset');
					exit();
				}
				// Desativa pedido de recuperação de senha expirado
				// mas não termina a execução da página, pois ainda é necessário
				// gerar um novo código de recuperação
				else {
					$db = Database::get_instance();
					try {
						$db->begin();
						Reset::update(['is_activated' => '0'])
							->where(['id' => $reset->id])
							->execute();
						$db->commit();
					}
					catch(\Exception $e) {
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}
			}
			// Gera uma palavra aleatório com 64 caracteres
			// Apatir dela será gerado o pedido de recuperação de senha
			// que será enviado por e-mail para o usuário
			$word = Hash::random_word(64);
			$reset = new Reset($word);
			$reset->user = $user->id;
			$db = Database::get_instance();
			// Transação no banco de dados responsável por salvar
			// o pedido
			try {
				$db->begin();
				Reset::save($reset);
				$this->data['send'] = true;
				$this->data['message'] = 'Um link para redefinir a senha foi enviado para o e-mail:';
				$this->data['email'] = $user->email;
				if(PURE_ENV == 'development') {
					echo '<br><br><br>'.DynamicHtml::link_to('login/validate_reset&k=' . $word . '&u=' . $user->id);
				} else {
					// @todo Melhorar implementação de send_password_reset
					// para que não seja necessário concatenar informações
					// no controller
					Mailer::send_password_reset(
						$user->name,
						$user->email,
						Res::str('app_title') . ' - Redefinir a senha',
						DynamicHtml::link_to('login/validate_reset&k=' . $word . '&u=' . $user->id)
					);
				}
				$this->render('login/reset');
				$db->commit();
				exit();
			}
			catch(\Exception $e) {
				$db->rollback();
				Mailer::bug_report($e);
				Request::redirect('error/unknown');
			}
		}
		// Não existe usuário com esse e-mail
		$this->data['message'] = 'Esse e-mail não está cadastrado.';
		$this->render('login/reset');
	}

	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/login/reset
	 *
	 * POST: realiza a alteração de senha do usuário no banco de
	 * dados
	 * GET: carrega formulário para a alteração
	 */
	public function reset_action()
	{
		$s = $this->session;
		$reset = $s->get('reset');
		// Verifica se existe algum pedido de alteração de senha válido
		// na session do usuário
		// Esse variavel de session vem de validate_reset
		// e permite que só exista alteração de senha no computador
		// que acessou o validate_reset
		if ($reset != false && $reset > (time() - 1800)){
			if(Request::is_POST()){
				$pass = $this->params->unpack('POST', ['re-password','password']);
				// Verifica a existencia dos parametros corretos em $_POST
				// e se ambas as senhas digitadas são identicas
				if($pass && $pass['re-password'] == $pass['password']) {
					// Exibe mensagem para o usuário que é necessário escolher
					// uma senha com no mínimo 6 caracteres
					if(strlen($pass['password']) < 6) {
						$this->data['error_message'] = 'A senha necessita ter mais de 6 caracteres.';
						$this->render('login/password');
						exit();
					}
					$password = new Password($pass['password']);
					$db = Database::get_instance();
					// Realiza a transação no banco de dados para alteração
					// de senha de usuário
					try {
						$db->begin();
						$id = Password::save($password);
						$user = User::find(['id' => $s->get('reset_id')]);
						User::update(['password' => $id])
							->where(['id' => $user->id])
							->execute();
						$reset = Reset::find(['user' => $s->get('reset_id'), 'is_activated' => true]);
						// Limpa dados de session e banco de dados
						Reset::update(['is_activated' => '0'])
							->where(['id' => $reset->id])
							->execute();
						$s->wipe('reset');
						$s->wipe('reset_id');
						// Autentica usuário
						Auth::authenticate($user->id, $user);
						$db->commit();
						Request::redirect('site/index');
					}
					catch(\Exception $e) {
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}
				// Exibe mensagem para o usuário que as senhas
				// digitadas são diferentes
				$this->data['error_message'] = 'As senhas não conferem.';
			}
			$this->render('login/password');
			exit();
		// Dados de session inexistentes ou fora de
		// validade
		// Limpa os dados de session e de banco de dados
		} else {
			$reset = Reset::find(['user' => $s->get('reset_id'), 'is_activated' => true]);
			if($reset) {
				$db = Database::get_instance();
				try {
					$db->begin();
					Reset::update(['is_activated' => '0'])
						->where(['id' => $reset->id])
						->execute();
					$s->wipe('reset');
					$s->wipe('reset_id');
					$db->commit();
				} catch(\Exception $e) {
					$db->rollback();
					Mailer::bug_report($e);
					Request::redirect('error/unknown');
				}
			}
		}
		// Redireciona para página de login
		Request::redirect('site/index');
	}

	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/login/validate_reset
	 *
	 * Valida link enviado para usuário recuperar a senha
	 */
	public function validate_reset_action()
	{
		$s = $this->session;
		$data = $this->params->unpack('GET', ['k','u']);
		$reset = Reset::find(['user' => $data['u'], 'is_activated' => true]);
		// Verifica dados de GET e busca valores no banco de dados
		// para que seja possível validar o pedido de recuperação
		if(isset($data['k']) && $reset) {
			// Verifica se o pedido é válido
			if (strtotime($reset->created) > (time() - 1800)){
				if(Reset::compare($reset, $data['k'])){
					$s->set('reset_id', $reset->user);
					$s->set('reset', time());
					Request::redirect('login/reset');
					exit();
				}
			// Pedido antigo de mais,
			// é necessário limpar os dados de alteração de senha
			// no banco de dados
			} else {
				$db = Database::get_instance();
				try {
					$db->begin();
					Reset::update(['is_activated' => '0'])
						->where(['id' => $reset->id])
						->execute();
					$db->commit();
				} catch(\Exception $e) {
					$db->rollback();
					Mailer::bug_report($e);
					Request::redirect('error/unknown');
				}
			}
		}
		// Carrega página de erro
		Request::redirect('error/index');
	}

	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/login/exit
	 *
	 * Realiza o logout na base de dados local
	 */
	public function exit_action()
	{
		Auth::revoke();
		Request::redirect('site/index');
	}

	/**
	 * Verifica se o usuário está logado e tentando acessar a página de login
	 * 
	 * Exige a utilização de SSL para acessar a página
	 */
	public function before()
	{
		if(PURE_ENV != 'development') {
			Request::require_ssl();
		}
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
		// Verifica se o captcha foi verificado
		if (!$this->validate_captcha()) 
		{
			$this->data['error_message'] = Res::str('captcha');
		} 
		// Verifica se o usuário existe
		else if($user === null) 
		{
			$this->data['error_message'] = Res::str('wrong_email');
		} 
		// Verifica se o usuário tem senha cadastrada
		else if (Password::find(['id' => $user->password]) === null) {
			$this->data['error_message'] = 'Nenhuma senha cadastrada. Clique em "Primeiro acesso"';
		} 
		// Verifica a senha enviada
		else if (!Password::compare(Password::find($user->password), $credential['password'])) {
			$this->data['error_message'] = Res::str('wrong_password');
			$this->data['old_email'] =  $credential['email'];
		} 
		// Realiza a autenticação
		else {
			Auth::authenticate($user->id, $user);
			return true;
		}
		return false;
	}

	/**
	 * Gera o captcha a partir de 10 tentivas a partir 
	 * de um intervaulo de 1800 segundos
	 */
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

	/**
	 * Valida o captcha digitado pelo usuário
	 * Caso exista a necessidade 
	 * @return boolean 
	 */
	private function validate_captcha() {
		$s = $this->session;
		$p = $this->params;
		// Existe a necessidade de utilizar captcha?
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
		// Nao existe, vai de boa
		return true;
	}
}