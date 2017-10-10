<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Session;

/**
 * Controller principal
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class SiteController extends Controller
{
	/**
	 * M�todo carregado ao acessar a rota
	 * ProjetoX/site/index e ProjetoX/
	 *
	 * Repons�vel por carregar a p�gina principal
	 */
	public function index_action()
	{
		
		$this->render();
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