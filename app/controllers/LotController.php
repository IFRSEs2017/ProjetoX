<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Session;
use App\Models\User;
use App\Models\Lot;

class LotController extends Controller
{

	public function list_action(){
		$this->data['list'] = Lot::find();
		$this->render('lot/list');
	}

	public function see_action($id){
		echo 'Ver:<br>';
		$item = Lot::find($id);
		var_dump($item);
	}

	public function insert_action(){
		// Insere pontos de vendas
		echo 'Insere';
	}

	public function delete_action($id){
		echo 'Delete:<br>';
		$item = Lot::find($id);
		var_dump($item);
	}

	public function update_action($id){
		echo 'Update:<br>';
		$item = Lot::find($id);
		var_dump($item);
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