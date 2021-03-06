<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use App\Models\User;

/**
 * Controller principal
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class SiteController extends Controller
{
	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/site/index e ProjetoX/
	 *
	 * Reponsável por carregar a página principal
	 */
	public function index_action()
	{
		if (User::is_admin()) {
			Request::redirect('admin/index');
		} else {
			Request::redirect('seller/index');
		}
	}

	/**
	 * Verifica se usu�rio est� logado
	 */
	public function before()
	{
		if (!Auth::is_authenticated())
		{
			Request::redirect('login/do');
		}
	}
}