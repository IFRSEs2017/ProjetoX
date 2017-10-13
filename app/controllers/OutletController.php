<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Auth;
use Pure\Utils\Request;
use App\Models\User;

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
		$users = User::select()->where(['is_actived' => true])->execute();
		foreach($users as $user) {
			$user->self = ($user->id == $this->session->get('uinfo')->id);
		}
		$this->data['list'] = $users;
		$this->render('outlet/list');
	}

	public function insert_action(){
		// Insere pontos de vendas
		echo 'Insere';
	}

	/**
	 * Método que realiza a desativação de algum usuário
	 *
	 * GET - Mostra formulario confirmando a desativação
	 * POST - Realiza a desativação
	 *
	 * @param mixed $id
	 */
	public function delete_action($id){
		if (Request::is_POST()){
			$user = User::find(['id' => intval($this->params->from_POST('id_to_delete'))]);
			if($user && $user->id !=  $this->session->get('uinfo')->id){
				User::update(['is_actived' => false])
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

	public function update_action(){
		// Atualiza pontos de vendas
		echo 'Atualiza';
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

		$this->data['user_name'] = $this->session->get('uinfo')->name;
	}
}