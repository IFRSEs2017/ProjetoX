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
		$this->render('seller/dashboard', 'default');

	}

	public function sell_action()
	{
		$this->render('seller/sell', 'default');

	}

	public function validate_sell_action()
	{
		$this->render('seller/validate_sell', 'default');

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
	}

}
