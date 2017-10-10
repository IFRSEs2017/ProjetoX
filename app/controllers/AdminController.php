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
		$this->data['page_name'] = 'Administrador';
		$this->render('admin/dashboard', 'default');	
		
	}

	public function edit_action()
	{
		$this->data['page_name'] = 'Administrador';
		$this->render('admin/edit', 'default');	
		
	}

	public function insert_action()
	{
		$this->data['page_name'] = 'Administrador';
		$this->render('admin/insert', 'default');	
		
	}

	public function delete_action()
	{
		$this->data['page_name'] = 'Administrador';
		$this->render('admin/delete', 'default');	
		
	}

	public function list_action()
	{
		$this->data['page_name'] = 'Administrador';
		$this->render('admin/list', 'default');	
		
	}

	public function report_action()
	{
		$this->render('admin/report', 'default');	
		
	}


	/**
	 * Verifica se usuário está logado
	 */
	public function before()
	{
		$this->data['page_name'] = 'Administrador';

		if (!Auth::is_authenticated())
		{
			Request::redirect('login/do');
		}

		if(!User::is_admin()){
			Request::redirect('seller/index');
		}
	}

}
