<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Session;
use App\Models\User;

class LotController extends Controller
{

	public function list_action(){
		$this->data['list'] = ['','',''];
		$this->render('lot/list');
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
		$this->data['is_admin'] = true;
	}
}