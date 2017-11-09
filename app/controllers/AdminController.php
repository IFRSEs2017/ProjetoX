<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use App\Models\User;
use App\Models\Ticket;
use App\Utils\Helpers;
use App\Models\Lot;

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
		$this->data['total_sold'] = Ticket::get_price_sum();
		$this->data['total_count'] = Ticket::get_count();
		$this->render('admin/report');
	}

	public function list_ticket_action() {
		$this->data['list'] = Ticket::select("*")
					->execute();
		$lots = Lot::select()
			->order_by(['start' => 'ASC'])
			->execute();
		for($i = 0; $i < count($lots); $i++) {
			$lots[$i]->number = $i + 1;
		}
		foreach($this->data['list'] as $item) {
			for($i = 0; $i < count($lots); $i++) {
				if ($lots[$i]->id == $item->lot) {
					$item->lot_number = $lots[$i]->number;
					break;
				}
			}

		}
		$this->render('admin/list_ticket');
	}

	public function report_per_day_action() {
		$this->data['list'] = Ticket::per_day();
		$this->render('admin/report_per_day');
	}

	public function report_per_lot_action() {
		$this->data['list'] = Lot::select()
					->order_by(['start' => 'ASC'])
					->execute();
		for($i = 0; $i < count($this->data['list']); $i++){
			$item = $this->data['list'][$i];
			$item->number = $i + 1;
			$sold = Ticket::count($item->id);
			$item->price = Ticket::get_price_sum_from($item->id);
			$item->sold = $sold;
		}
		$this->render('admin/report_per_lot');
	}

	public function report_per_seller_action() {
		$this->data['list'] = Ticket::per_user();
		$this->render('admin/report_per_seller');
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
				if (Helpers::date_format($obj->y, 'd/m') == $range[$i]) {
					$array[$range[$i]] = $obj->x;
					break;
				}
			}
		}
		return $array;
	}

}
