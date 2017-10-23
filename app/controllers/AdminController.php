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
class AdminController extends Controller
{
	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/site/index e ProjetoX/
	 *
	 * Reponsável por carregar a página principal
	 */
	public function index_action()
	{
		$this->data['page_name'] = 'Home';
		$this->render('admin/dashboard');
	}

	/**
	 * Controler de relatório.
	 * Ainda não implementado.
	 */
	public function report_action()
	{
		Request::redirect('error/index');
		$this->render('admin/report', 'default');
	}

	/**
	 * Verifica se usuário está logado
	 */
	public function before()
	{
		if (!Auth::is_authenticated())
		{
			Request::redirect('login/do');
		}

		if(!User::is_admin()){
			Request::redirect('seller/index');
		}

		$this->data['user_name'] = $this->session->get('uinfo')->name;
		$this->data['is_admin'] = true;
	}

}
