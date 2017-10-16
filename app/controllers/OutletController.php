<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Auth;
use Pure\Utils\Request;
use App\Models\User;
use App\Models\Password;
use App\Utils\Helpers;

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

	public function list_action(){
		// Lista pontos de vendas
		$this->data['list'] = User::select()->execute();
		$this->render('outlet/list');
	}
	//
	
	

	public function insert_action(){
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', ['form_name_user', 'form_email', 'form_privilege', 'form_rg', 'form_cpf']);
			
			$errors = [];
			$user = new User();
			Helpers::string_validation($data['form_name_user']) ? $user->name = $data['form_name_user'] : array_push($errors, 'Nome Inválido');
			Helpers::email_validation($data['form_email']) ? $user->email = $data['form_email'] : array_push($errors, 'Email Inválido');
			//Helpers::rg_validation($data['form_rg']) ? $user->rg = $data['form_rg'] : array_push($errors, 'RG Inválido');
			//Helpers::cpf_validation($data['form_cpf']) ? $user->cpf = $data['form_cpf'] : array_push($errors, 'CPF Inválido');
			$user->is_admin = intval($data['form_privilege']);
			$user_db = User::find(['email' => $user->email]);
			
			if(count($errors) > 0){
				$this->data['errors'] = $errors;
				$this->data['title'] = "Ops!";
				$this->data['class'] = "class = 'alert alert-danger'";
			}elseif($user_db == !NULL){					
				$this->data['message'] = "Usuário já cadastrado";
				$this->data['title'] = "Ops!";
				$this->data['class'] = "class = 'alert alert-danger'";
			}else{
				User::save($user);
				Request::redirect('outlet/list');
			}
		}
		$this->render('outlet/insert');
		
	}

	
	
	public function delete_action(){
		// Exclui pontos de vendas
		echo 'Exclui';
	}
	//eu
	public function update_action($id){
		// Atualiza pontos de vendas
		// if (Request::is_POST()){
		// 	$user_db = User::find(['id' => intval($this->params->from_POST('id_to_update'))]);
		// 	echo '<br><br><br><br>';
		// 	var_dump($user_db);
		// 	$this->data['user'] = $user_db;
			
		// }
		// Insere pontos de vendas
		//$this->render('outlet/update');
		
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
	}
}