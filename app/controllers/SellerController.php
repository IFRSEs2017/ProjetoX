<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Session;
use App\Models\User;

/**
 * Controller principal
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class SellerController extends Controller
{
	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/site/index e ProjetoX/
	 *
	 * Reponsável por carregar a página principal
	 */
	public function index_action()
	{
		$this->render('seller/dashboard');
	}

	public function sell_action(){
		$this->render('seller/sell');
	}

	/**
	 * Verifica se usuário está logado
	 */
	public function before()
	{
		$this->data['page_name'] = 'Vendedor';

		if (!Auth::is_authenticated())
		{
			Request::redirect('login/do');
		}

		if(User::is_admin()){
			Request::redirect('admin/index');
		}
		$this->data['is_admin'] = false;
	}

}
