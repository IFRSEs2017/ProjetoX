<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Session;
use App\Models\User;
/**
 * TicketController short summary.
 *
 * TicketController description.
 *
 * @version 1.0
 * @author marce
 */
class TicketController extends Controller
{

	public function list_action(){
		// Lista pontos de vendas
		echo 'Lista';
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
	}
}