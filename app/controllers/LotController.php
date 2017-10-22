<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use App\Utils\Helpers;
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

	/**
	 * Insere um lote no banco de dados
	 *
	 * GET - Mostra fomrulario inserção
	 * POST - Realiza a inserção em banco
	 */
	public function insert_action(){
		// Insere pontos de vendas
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', ['lot_amount', 'lot_valuation', 'start', 'end']);
			$errors = [];
			$lot = new Lot();

			Helpers::number_validation($data['lot_amount']) ? $lot->amount = $data['lot_amount'] : array_push($errors, 'Quantidade Inválido');
			Helpers::value_validation($data['lot_valuation']) ? $lot->valuation = $data['lot_valuation'] : array_push($errors, 'Valor Inválido');
			Helpers::date_validation($data['start']) ? $lot->start = Helpers::date_ajust($data['start']) : array_push($errors, 'Data de Início Inválido');
			Helpers::date_validation($data['end']) ? $lot->end = Helpers::date_ajust($data['end']) : array_push($errors, 'Data Final Inválido');
			$lot->creator = $this->session->get('uinfo')->id;
			$lot->is_activated = 1;

			if(count($errors) > 0){
				$this->data['errors'] = $errors;
				$this->data['title'] = "Ops!";
				$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
				$this->data['user'] = $this->session->get('uinfo')->id;
			}else{
				Lot::save($lot);
				Request::redirect('lot/list');
			}
		}
		$this->render('lot/insert');
	}

	/**
	 * Método que deleta um lote do banco de dados
	 *
	 * GET - Mostra formulario de confirmando do delete
	 * POST - Realiza o delete
	 *
	 * @param mixed $id
	 */
	public function delete_action($id){
		if (Request::is_POST()){
			$lot = Lot::find(['id' => intval($this->params->from_POST('id_to_delete'))]);
			Lot::delete()
					->where(['id' => $lot->id])
					->execute();
				Request::redirect('lot/list');
			}else {
				$lot = Lot::find(['id' => intval($id)]);
				if ($lot == false) {
					$this->data['message'] = 'O lote que você está tentando excluir não existe.';
				} else {
					$this->data['lot'] = $lot;
				}
			}
	$this->render('lot/delete');

	}

	public function update_action($id){
		if (Request::is_POST()){
		$data = $this->params->unpack('POST', ['lot_id', 'lot_amount', 'lot_valuation', 'start', 'end']);
		$lot_db = Lot::find(['id' => intval($data['lot_id'])]);
		if ($lot_db){
			$errors = [];
			Helpers::number_validation($data['lot_amount']) ? $lot_db->amount = $data['lot_amount'] : array_push($errors, 'Quantidade Inválido');
			Helpers::value_validation($data['lot_valuation']) ? $lot_db->valuation = $data['lot_valuation'] : array_push($errors, 'Valor Inválido');
			Helpers::date_validation($data['start']) ? $lot_db->start = Helpers::date_ajust($data['start']) : array_push($errors, 'Data de Início Inválido');
			Helpers::date_validation($data['end']) ? $lot_db->end = Helpers::date_ajust($data['end']) : array_push($errors, 'Data Final Inválido');
			$lot_db->creator = $this->session->get('uinfo')->id;

			//$lot_db->is_activated = 1;
			if(count($errors) > 0){
				$this->data['errors'] = $errors;
				$this->data['title'] = "Ops!";
				$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
		
				$lot_db->start = Helpers::date_reajust($lot_db->start);
				$lot_db->end = Helpers::date_reajust($lot_db->end);
				
				$this->data['lot'] = $lot_db;
				$this->render('lot/update');
				exit();
			}else{
				Lot::update(['amount' => $lot_db->amount, 'start' => $lot_db->start, 'end' => $lot_db->end, 'valuation' => $lot_db->valuation])
					->where(['id' => $lot_db->id])
					->execute();
				Request::redirect('lot/list');
			}
		}
	} else if (intval($id)) {
		//atualiza lotes
		$lot_db = Lot::find(['id' => intval($id)]);
		$lot_db->start = Helpers::date_reajust($lot_db->start);
		$lot_db->end = Helpers::date_reajust($lot_db->end);
		
		$this->data['lot'] = $lot_db;
		$this->render('lot/update');
		exit();

	}
	Request::redirect('lot/list');
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