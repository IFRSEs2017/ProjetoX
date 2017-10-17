<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Auth;
use Pure\Utils\Request;
use App\Models\User;
use App\Utils\Helpers;
use Pure\Utils\Session;

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
		$users = User::select()->where(['is_activated' => true])->execute();
		foreach($users as $user) {
			$user->self = ($user->id == $this->session->get('uinfo')->id);
		}
		$this->data['list'] = $users;
		$this->render('outlet/list');
	}

	/**
	 * Insere um usuário no banco de dados
	 *
	 * GET - Mostra fomrulario inserção
	 * POST - Realiza a inserção em banco
	 */
	public function insert_action(){
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', ['form_name_user', 'form_email', 'form_privilege', /* 'form_rg', 'form_cpf'*/]);

			$errors = [];
			$user = new User();
			Helpers::string_validation($data['form_name_user']) ? $user->name = $data['form_name_user'] : array_push($errors, 'Nome Inválido');
			Helpers::email_validation($data['form_email']) ? $user->email = $data['form_email'] : array_push($errors, 'E-mail Inválido');
			//Helpers::cpf_validation($data['form_cpf']) ? $user->cpf = $data['form_cpf'] : array_push($errors, 'CPF Inválido');
			//Helpers::rg_validation($data['form_rg']) ? $user->rg = $data['form_rg'] : array_push($errors, 'RG Inválido');
			$user->is_admin = intval($data['form_privilege']);
			$user->is_activated = 1;

			$user_db = User::find(['email' => $user->email]);
			if(count($errors) > 0){
				$this->data['errors'] = $errors;
				$this->data['title'] = "Ops!";
				$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
				$this->data['user'] = $user;
			}elseif($user_db != NULL){
				$this->data['message'] = "Usuário já cadastrado";
				$this->data['title'] = "Ops!";
				$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
				$this->data['user'] = $user;
			}else{
				User::save($user);
				Request::redirect('outlet/list');
			}
		}
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
			if($user && $user->id !=  $this->session->get('uinfo')->id){
				User::delete()
					->where(['id' => $user->id])
					->execute();
				Request::redirect('outlet/list');
			} else {
				$this->data['message'] = 'Não foi possível desativar o usuário.';
			}
		} else {
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
		//Atualiza pontos de vendas
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', ['form_id', 'form_name_user', 'form_email', 'form_privilege'/*, 'form_rg', 'form_cpf'*/], false);
			$user_db = User::find(['id' => intval($data['form_id'])]);
			if ($user_db){
				$errors = [];
				Helpers::string_validation($data['form_name_user']) ? $user_db->name = $data['form_name_user'] : array_push($errors, 'Nome Inválido');
				Helpers::email_validation($data['form_email']) ? $user_db->email = $data['form_email'] : array_push($errors, 'Email Inválido');
				/*$cpf = User::find(['cpf' => intval($data['form_cpf'])]);
				if ($cpf != false && $cpf->id != $user_db->id) {
					array_push($errors, 'O CPF já está em uso');
				} else {
					Helpers::cpf_validation($data['form_cpf']) ? $user_db->cpf = $data['form_cpf'] : array_push($errors, 'CPF Inválido');
				}
				Helpers::rg_validation($data['form_rg']) ? $user->rg = $data['form_rg'] : array_push($errors, 'RG Inválido');
				Helpers::cpf_validation($data['form_cpf']) ? $user->cpf = $data['form_cpf'] : array_push($errors, 'CPF Inválido');*/
				if($user_db->id == Session::get_instance()->get('uinfo')->id){
					$user_db->is_admin = true;
				} else {
					$user_db->is_admin = intval($data['form_privilege']);
				}
				$user_db->is_activated = 1;
				if(count($errors) > 0){
					$this->data['errors'] = $errors;
					$this->data['title'] = "Ops!";
					$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
					$this->data['user'] = $user_db;
					$this->render('outlet/update');
					exit();
				}else{
					User::update(['name' => $user_db->name, 'email' => $user_db->email, /*'cpf' => $user_db->cpf, */'is_admin' => $user_db->is_admin])
						->where(['id' => $user_db->id])
						->execute();
					Request::redirect('outlet/list');
				}
			}
		} else if (intval($id)) {
			//Insere pontos de vendas
			$user_db = User::find(['id' => intval($id)]);
			$this->data['user'] = $user_db;
			$this->render('outlet/update');
			exit();

		}
		Request::redirect('outlet/list');
	}

	/**
	 * Verifica se usu?rio est? logado
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