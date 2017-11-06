<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use App\Models\User;
use App\Models\Ticket;
use App\Utils\Helpers;

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
		$counts = Ticket::last_month_count();
		$this->data['last_month_count'] = $this->range_to_chart($counts, 'last month', 'today');
		$fature = Ticket::last_month_fature();
		$this->data['last_month_fature'] = $this->range_to_chart($fature, 'last month', 'today');
		$this->render('admin/report');
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

	private function range_to_chart($obj_arr, $start, $end){
		$array = [];
		$range = Helpers::date_range(
			date('Y-m-d', strtotime($start)),
			date('Y-m-d', strtotime($end))
		);
		for($i = 0; $i < count($range); $i++) {
			$array[$range[$i]] = '0';
			foreach($obj_arr as $obj) {
				if (Helpers::date_format($obj->y) == $range[$i]) {
					$array[$range[$i]] = $obj->x;
					break;
				}
			}
		}
		return $array;
	}

}
