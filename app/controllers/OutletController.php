<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Auth;
use Pure\Utils\Request;
use App\Models\User;
use App\Utils\Helpers;
use Pure\Utils\Session;
use Pure\Db\Database;
use App\Utils\Mailer;

/**
 * OutletController short summary.
 *
 * OutletController description.
 *
 * @version 1.0
 * @author marce
 */
class OutletController extends Controller
{

	/**
	 * Lista ponto de vendas e usuários
	 */
	public function list_action(){
		$users = User::select()
			->where(['is_activated' => true])
			->execute();
		foreach($users as $user) {
			$user->self = ($user->id == $this->session->get('uinfo')->id);
		}
		$this->data['list'] = $users;
		$this->render('outlet/list');
	}

	/**
	 * Insere um usuário no banco de dados
	 *
	 * GET - Mostra formulário inserção
	 * POST - Realiza a inserção em banco
	 */
	public function insert_action(){
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', ['form_name_user', 'form_email', 'form_privilege']);
			// Se todos os dados do forulário foram enviado
			if ($data) {
				$errors = [];
				$user = new User();
				// Valida os campos necessários para gerar um usuário
				// no banco de dados
				Helpers::string_validation($data['form_name_user']) ?
					$user->name = $data['form_name_user'] :
					array_push($errors, 'Nome Inválido');
				Helpers::email_validation($data['form_email']) ?
					$user->email = $data['form_email'] :
					array_push($errors, 'E-mail Inválido');
				$user->is_admin = intval($data['form_privilege']);
				$user->is_activated = 1;
				$user_db = User::find(['email' => $user->email]);
				// Caso exista erros no formulário:
				// preenche as caixas de dialogo para alertar o usuário
				if(count($errors) > 0){
					$this->data['errors'] = $errors;
					$this->data['title'] = "Ops!";
					$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
					$this->data['user'] = $user;
				}
				// Caso o usuário exista, mas esteja desativado:
				// atualiza informações e ativa novamente
				else if($user_db && $user_db->is_activated == false){
					$db = Database::get_instance();
					try {
						$db->begin();
						$user_db->name = $user->name;
						$user_db->email = $user->email;
						$user_db->is_admin = $user->is_admin;
						$user_db->is_activated = true;
						User::save($user_db);
						$db->commit();
						Request::redirect('outlet/list');
					}
					catch(\Exception $e) {
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}
				// Caso não haja errors, mas o usuário já esteja
				// cadastrado e ativo
				else if($user_db){
					$this->data['message'] = "Usuário já cadastrado";
					$this->data['title'] = "Ops!";
					$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
					$this->data['user'] = $user;
				}
				// Realiza o cadastro de novo usuário
				else {
					$db = Database::get_instance();
					try {
						$db->begin();
						User::save($user);
						$db->commit();
						Request::redirect('outlet/list');
					}
					catch(\Exception $e) {
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}
			} else {
				Request::redirect('error/index');
			}
		}
		// Carrega formulário de cadastro de usuário
		$this->render('outlet/insert');
	}

	/**
	 * Método que realiza a desativação de algum usuário
	 *
	 * GET - Mostra formulario de confirmando a desativação
	 * POST - Realiza a desativação
	 *
	 * @param mixed $id
	 */
	public function delete_action($id){
		if (Request::is_POST()){
			$user = User::find(['id' => intval($this->params->from_POST('id_to_delete'))]);
			// Se o usuário existir e for diferente do usuário atual
			if($user && $user->id != $this->session->get('uinfo')->id){
				$db = Database::get_instance();
				try {
					$db->begin();
					User::update(['is_activated' => false])
						->where(['id' => $user->id])
						->execute();
					$db->commit();
					Request::redirect('outlet/list');
				}
				catch(\Exception $e) {
					$db->rollback();
					Mailer::bug_report($e);
					Request::redirect('error/unknown');
				}
				// Algum erro na desativação
			} else {
				$this->data['message'] = 'Não foi possível desativar o usuário.';
			}
		}
		// Carregamento de formulário de desativação
		else {
			$user = User::find(['id' => intval($id)]);
			if ($user == false) {
				$this->data['message'] = 'O usuário que você está tentando excluir não existe.';
			} else if ($user->id ==  $this->session->get('uinfo')->id) {
				$this->data['message'] = 'Você não pode excluir sua própria conta de usuário.';
			} else {
				$this->data['user'] = $user;
			}
		}
		$this->render('outlet/delete');
	}

	/**
	 * Atualiza usuário no banco de dados
	 *
	 * GET - Mostra formulário para atualização
	 * POST - Realiza o update
	 *
	 * @param mixed $id
	 */
	public function update_action($id){
		// Realiza a atualização de pontos de venda/usuário
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', [
				'form_id',
				'form_name_user',
				'form_email',
				'form_privilege'], false);
			$user_db = User::find(['id' => intval($data['form_id'])]);
			// Existe um usuário válido com essa informação em
			// banco de dados
			if ($user_db){
				$errors = [];
				// Valida os campos necessários para atualização
				// do usuário
				Helpers::string_validation($data['form_name_user']) ?
					$user_db->name = $data['form_name_user'] :
					array_push($errors, 'Nome Inválido');
				Helpers::email_validation($data['form_email']) ?
					$user_db->email = $data['form_email'] :
					array_push($errors, 'Email Inválido');
				// Administra relação de administração
				// sabendo que não é possível retirar a permissão
				// de administrador de si mesmo
				if($user_db->id == Session::get_instance()->get('uinfo')->id){
					$user_db->is_admin = true;
				} else {
					$user_db->is_admin = intval($data['form_privilege']);
				}
				$user_db->is_activated = 1;
				// Caso exista algum erro no formulário
				if(count($errors) > 0){
					$this->data['errors'] = $errors;
					$this->data['title'] = "Ops!";
					$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
					$this->data['user'] = $user_db;
					$this->render('outlet/update');
					exit();
				}
				// Não existindo, inicia o processo de
				// atualização no banco de dados
				else{
					$db = Database::get_instance();
					try {
						$db->begin();
						User::update([
							'name' => $user_db->name,
							'email' => $user_db->email,
							'is_admin' => $user_db->is_admin])
							->where(['id' => $user_db->id])
							->execute();
						$db->commit();
						Request::redirect('outlet/list');
					}
					catch(\Exception $e) {
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}
			}
		}
		// Carrega formulário de autalização
		// de um ponto de venda específico
		// enviado por parametro
		else if (intval($id)) {
			$user_db = User::find(['id' => intval($id)]);
			$this->data['user'] = $user_db;
			$this->render('outlet/update');
			exit();

		}
		Request::redirect('outlet/list');
	}

	/**
	 * Verifica se usuário está logado
	 *
	 * Somente administradores podem acessar esse controller
	 */
	public function before()
	{
		if (!Auth::is_authenticated())
		{
			Request::redirect('login/do');
		}
		if(!User::is_admin()){
			Request::redirect('seller/index');
		}
		$this->data['user_name'] = $this->session->get('uinfo')->name;
		$this->data['is_admin'] = true;
	}
}