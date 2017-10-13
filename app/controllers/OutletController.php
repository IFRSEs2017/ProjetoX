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
		$users = User::select()->execute();
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

	public function delete_action(){
		// Exclui pontos de vendas
		echo 'Exclui';
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