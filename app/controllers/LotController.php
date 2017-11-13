<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use App\Utils\Helpers;
use Pure\Utils\Auth;
use App\Models\Lot;
use Pure\Db\Database;
use App\Utils\Mailer;
use App\Models\Ticket;

/**
 * Controller que gerencia ações de lotes de ingressos
 */
class LotController extends Controller
{

	/**
	 * Rota carrega ao acessar
	 * ProjetoX/lot/lis
	 *
	 * Lista para
	 */
	public function list_action(){
		$this->data['list'] = Lot::select()
			->order_by(['start' => 'ASC'])
			->execute();
		// Conta quantidade de ingressos restantes
		// para cada lote
		foreach($this->data['list'] as $lot){
			$sold = Ticket::count($lot->id);
			$lot->remain = $lot->amount - $sold;
		}
		$this->render('lot/list');
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
			// Verifica se todos os dados necessarios para
			// cadastrar um lote estão presentes
			// no formulário
			if ($data) {
				$lot = new Lot();
				// Valida os campos necessário para cadastrar um
				// lote no banco de dados
				Helpers::number_validation($data['lot_amount']) ?
					$lot->amount = intval($data['lot_amount']) :
					array_push($errors, 'Quantidade Inválido');
				Helpers::value_validation($data['lot_valuation']) ?
					$lot->valuation = floatval($data['lot_valuation']) :
					array_push($errors, 'Valor Inválido');
				Helpers::date_validation($data['start']) ?
					$lot->start = Helpers::date_ajust($data['start']) :
					array_push($errors, 'Data de Início Inválido');
				Helpers::date_validation($data['end']) ?
					$lot->end = Helpers::date_ajust($data['end']) :
					array_push($errors, 'Data Final Inválido');
				if (count($errors) == 0 &&
					strtotime($data['start']) > strtotime($data['end'])) {
					array_push($errors, 'A data de inicio de vendas não pode ser superior a de final.');
				}
				$lot->creator = $this->session->get('uinfo')->id;
				$lot->is_activated = 1;
				// Caso existe um erro no formulário,
				// determina para o usuário
				// quais campos necessita alterar
				if(count($errors) > 0){
					$this->data['errors'] = $errors;
					$this->data['title'] = "Ops!";
					$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
					$this->data['user'] = $this->session->get('uinfo')->id;
					// Salva os dados em banco
				}else{
					$db = Database::get_instance();
					try {
						$db->begin();
						Lot::save($lot);
						$db->commit();
						Request::redirect('lot/list');
					}
					catch(\Exception $e) {
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}
			} else {
				Request::redirect('error/index');
			}
		}
		$this->render('lot/insert');
	}

	/**
	 * Método que deleta um lote do banco de dados
	 *
	 * GET - Mostra formulario de confirmação delete
	 * POST - Realiza o delete
	 *
	 * @param mixed $id
	 */
	public function delete_action($id){
		// Caso a requisição seja POST
		if (Request::is_POST()){
			$lot = Lot::find(['id' => intval($this->params->from_POST('id_to_delete'))]);
			if ($lot) {
				$tickets = Ticket::find(['lot' => $lot->id]);
				// Verifica se já houve venda de ingressos
				// nesse lote, para que não existe ingressos
				// sem um lote cadastrado
				if($tickets) {
					$this->data['message'] = 'O lote que você está tentando já teve ingressos '.
						'vendidos. <br>Considere alterar o lote ao invés de excluí-lo.';
					$this->render('lot/delete');
					exit();
					// Realiza a exclusão no banco de dados
				} else {
					$db = Database::get_instance();
					try {
						$db->begin();
						Lot::delete()
							->where(['id' => $lot->id])
							->execute();
						$db->commit();
						Request::redirect('lot/list');
					}
					catch(\Exception $e) {
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}
			}
			// Carrega a confirmação de exclusão
		} else {
			$lot = Lot::find(['id' => intval($id)]);
			if ($lot == false) {
				$this->data['message'] = 'O lote que você está tentando excluir não existe.';
			} else {
				$this->data['lot'] = $lot;
			}
		}
		$this->render('lot/delete');
	}

	/**
	 * Método que atualiza um lote do banco de dados
	 *
	 * GET - Mostra formulário de confirmação de atualização
	 * POST - Realiza a atualização dos dados em banco
	 *
	 * @param mixed $id 
	 */
	public function update_action($id){
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', ['lot_id', 'lot_amount', 'lot_valuation', 'start', 'end']);
			if($data) {
				$lot_db = Lot::find(['id' => intval($data['lot_id'])]);
				if ($lot_db){
					$errors = [];
					// Validação dos campos de lote
					// Verifica a integridade dos dados
					Helpers::number_validation($data['lot_amount']) ?
						$lot_db->amount = $data['lot_amount'] :
						array_push($errors, 'Quantidade Inválido');
					Helpers::value_validation($data['lot_valuation']) ?
						$lot_db->valuation = $data['lot_valuation'] :
						array_push($errors, 'Valor Inválido');
					Helpers::date_validation($data['start']) ?
						$lot_db->start = Helpers::date_ajust($data['start']) :
						array_push($errors, 'Data de ínicio inválida');
					Helpers::date_validation($data['end']) ?
						$lot_db->end = Helpers::date_ajust($data['end']) :
						array_push($errors, 'Data de final inválida');
					// Verifica se a data de inicio é anterior a data de final
					if (count($errors) == 0 &&
						strtotime($lot_db->start) > strtotime($lot_db->end)) {
						array_push($errors, 'A data de inicio de vendas não pode ser superior a de final.');
					}
					// Verifica se o usuário não está
					// tentando diminuir a quantidade de ingressos
					// para menos do que já foi vendido
					if($lot_db->amount < Ticket::count($lot_db->id)) {
						array_push($errors, 'Você não pode diminuir a quantidade de ingressos' .
							' para menos do que já foi vendido. ');
					}
					$lot_db->creator = $this->session->get('uinfo')->id;
					// Se houver algum erro na validação do formulário
					if(count($errors) > 0){
						$this->data['errors'] = $errors;
						$this->data['title'] = "Ops!";
						$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
						$lot_db->start = Helpers::date_reajust($lot_db->start);
						$lot_db->end = Helpers::date_reajust($lot_db->end);
						$this->data['lot'] = $lot_db;
						$this->render('lot/update');
						exit();
					}
					// Caso não exista nenhum erro
					// realiza a atualização no banco de dados
					else{
						$db = Database::get_instance();
						try {
							// Realiza a migração no banco de dados
							$db->begin();
							Lot::update([
								'amount' => $lot_db->amount,
								'start' => $lot_db->start,
								'end' => $lot_db->end,
								'valuation' => $lot_db->valuation])
								->where(['id' => $lot_db->id])
								->execute();
							$db->commit();
							Request::redirect('lot/list');
						}
						catch(\Exception $e) {
							$db->rollback();
							Mailer::bug_report($e);
							Request::redirect('error/unknown');
						}
					}
				}
			}
			// Caso o formulário não seja válido
			else {
				Request::redirect('error/index');
			}
		} else if (intval($id)) {
			// Realiza carregamento do formulário de
			// atualização de lotes
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
	 * Verifica se usuário está logado
	 * Somente administradores podem acessar essa rota
	 */
	public function before()
	{
		if (!Auth::is_authenticated())
		{
			Request::redirect('login/do');
		}

		if (!User::is_admin()) 
		{
			Request::redirect('error/index');
		}

		$this->data['user_name'] = $this->session->get('uinfo')->name;
		$this->data['is_admin'] = true;
	}
}