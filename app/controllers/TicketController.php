<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use App\Models\Ticket;
use App\Utils\Helpers;
use Pure\Db\Database;
use App\Utils\Mailer;
use Pure\Routes\Route;
use App\Models\User;
use Pure\Utils\Hash;
use Pure\Utils\DynamicHtml;
use App\Controllers\SellerController;
use Pure\Utils\Res;

/**
 * TicketController short summary.
 *
 * TicketController description.
 *
 * @version 1.0
 * @author marce
 */
class TicketController extends Controller
{

	/**
	 * Lista tickets
	 */
	public function list_action()
	{
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', ['search']);
			if((Helpers::string_validation($data['search'])) && (!Helpers::rg_validation($data['search'])))
			{
				$ticket_db = Ticket::select()
					->where_like(['owner_name' => '%' . $data['search'] . '%'])
					->execute();
				$ticket_db ? $this->data['search_result'] = $ticket_db : $this->data['not_found_result'] = true;
			}
			else if(Helpers::email_validation($data['search']))
			{

				$ticket_db = Ticket::select()
					->where_like(['owner_email' =>  '%' . $data['search'] . '%'])
					->execute();
				$ticket_db ? $this->data['search_result'] = $ticket_db : $this->data['not_found_result'] = true;
			}
			else if(Helpers::cpf_validation($data['search']))
			{

				$ticket_db = Ticket::select()
					->where_like(['owner_cpf' =>  '%' . $data['search'] . '%'])
					->execute();
				$ticket_db ? $this->data['search_result'] = $ticket_db : $this->data['not_found_result'] = true;
			} else {
				$this->data['not_found_result'] = true;
			}
		}
		$tickets = Ticket::select()
			->order_by(['created' => 'ASC'])
			->execute();
		$this->data['list'] = $tickets;
		$this->render('ticket/list');
	}

	/**
	 * Insere um ticket (não utilizando)
	 */
	public function insert_action(){
		Request::redirect('error/index');
	}

	/**
	 * Exclui ingresso do banco de dados
	 * @param mixed $id
	 */
	public function delete_action($id)
	{
		if (Request::is_POST())
		{
			$ticket = Ticket::find(['id' => intval($this->params->from_POST('id_to_delete'))]);
			if($ticket)
			{
				$db = Database::get_instance();
				try{
					$db->begin();
					Ticket::delete()
						->where(['id' => $ticket->id])
						->execute();
					$db->commit();
					Request::redirect('ticket/list');
				}
				catch(\Exception $e)
				{
					$db->rollback();
					Mailer::bug_report($e);
					Request::redirect('error/unknown');
				}
			}
		}
		// Carrega a confirmação de exclusão
		else
		{
			$ticket = Ticket::find(['id' => intval($id)]);
			if($ticket == false)
			{
				$this->data['message'] = 'O ingresso não existe';
			}
			else
			{
				$this->data['ticket'] = $ticket;
			}
		}
		$this->render('ticket/delete');
	}

	/**
	 * Gera um novo ingresso e reenvia
	 * @param mixed $id
	 */
	public function send_again_action($id)
	{
		$ticket = Ticket::find(['id' => intval($id)]);
		if ($ticket) {
			if (Request::is_POST()){
				$db = Database::get_instance();
				try {
					$db->begin();
					$ticket_secret = Hash::random_word(64);
					$new = new Ticket($ticket_secret);
					$new->id = $ticket->id;
					$new->lot = $ticket->lot;
					$new->seller = $ticket->seller;
					$new->price = $ticket->price;
					$new->owner_name = $ticket->owner_name;
					$new->owner_email = $ticket->owner_email;
					$new->owner_cpf = $ticket->owner_cpf;
					Ticket::save($new);
					$code = SellerController::create_qrcode($ticket_secret, $new->password, $new->id);
					if(PURE_ENV == 'development') {
						echo '<br><br><br>' .
						DynamicHtml::link_to('ticket/validate&t=' .
						$ticket_secret .
						'&i=' . $id);
					} else {
						Mailer::send_ticket(
							$ticket->owner_name,
							$ticket->owner_email,
							Res::str('app_title') .
							' - Seu ingresso virtual',
							$code);
					}
					$this->data['message'] = 'Um novo ingresso foi gerado com sucesso!' .
					'<br> Confira o e-mail para confirmar a venda:';
					$this->data['email'] = $ticket->owner_email;
					$this->data['qrcode'] = DynamicHtml::link_to('app/assets/images/' . $code . '.png');
					$this->render('seller/sold');
					$db->commit();
					exit();
				}
				catch(\Exception $e) {
					$db->rollback();
					Mailer::bug_report($e);
					Request::redirect('error/unknown');
				}
			} else {
				$this->data['ticket']= $ticket;
				$this->render('ticket/send_again');
			}
			exit();
		}
		Request::redirect('error/index');
	}

	/**
	 * Atualiza ingresso
	 * @param mixed $id
	 */
	public function update_action($id)
	{
		// Realiza a atualização de pontos de venda/usuário
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', [
				'ticket_id',
				'owner_name',
				'owner_email',
				'owner_cpf'
			], false);

			$ticket_db = Ticket::find(['id' => intval($data['ticket_id'])]);
			// Existe um ticket válido com essa informação em
			// banco de dados
			if($ticket_db){
				$errors = [];
				// Valida os campos necessários para atualização
				Helpers::string_validation($data['owner_name']) ?
					$ticket_db->owner_name = $data['owner_name'] :
					array_push($errors, 'Nome Inválido');
				Helpers::email_validation($data['owner_email']) ?
					$ticket_db->owner_email = $data['owner_email'] :
					array_push($errors, 'E-mail Inválido');
				Helpers::cpf_validation($data['owner_cpf']) ?
					$ticket_db->owner_cpf = $data['owner_cpf'] :
					array_push($errors, 'CPF Inválido');
				$other = Ticket::find(['owner_cpf' => $data['owner_cpf']]);
				if ($other) {
					array_push($errors,
						'Já foi vendido um ingresso para o CPF ' .
						$data['owner_cpf'] . '.');
				}

				// Caso exista algum erro no formulário
				if(count($errors) > 0){
					$this->data['errors'] = $errors;
					$this->data['title'] = "Ops!";
					$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
					$this->render('ticket/update');
					exit();
				}
				// Não existindo, inicia o processo de
				// atualização no banco de dados
				else{
					$db = Database::get_instance();
					try{
						$db->begin();
						Ticket::update([
							'owner_name' => $ticket_db->owner_name,
							'owner_email' => $ticket_db->owner_email,
							'owner_cpf' => $ticket_db->owner_cpf])
							->where(['id' => $ticket_db->id])
							->execute();
						$db->commit();
						Request::redirect('ticket/list');
					}
					catch(\Exception $e)
					{
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}

			}

		}
		// Carrega formulário de autalização
		else if(intval($id))
		{
			$ticket_db = Ticket::find(['id' => intval($id)]);
			$this->data['ticket'] = $ticket_db;
			$this->render('ticket/update');
			exit();
		}
		Request::redirect('ticket/list');
	}

	/**
	 * Valida (consome) ingresso
	 */
	public function validate_action()
	{
		if (Request::is_POST()){
			$code = $this->params->unpack('POST', ['secret', 'ticket']);
			if ($code) {
				$ticket = Ticket::find(['id' => intval($code['ticket'])]);
				if(Ticket::compare($ticket, $code['secret'])) {
					$ticket->validated = true;
					$db = Database::get_instance();
					try{
						$db->begin();
						Ticket::save($ticket);
						$db->commit();
						Request::redirect('ticket/validate&t=' . $code['secret'] . '&i=' . $code['ticket']);
					}
					catch(\Exception $e)
					{
						$db->rollback();
						Mailer::bug_report($e);
						Request::redirect('error/unknown');
					}
				}
			}
		} else {
			$code = $this->params->unpack('GET', ['t', 'i']);
			if ($code) {
				$ticket = Ticket::find(['id' => intval($code['i'])]);
				if(Ticket::compare($ticket, $code['t'])) {
					$this->data['ticket'] = $ticket;
					$this->data['secret'] = $code['t'];
					$this->data['view'] = Auth::is_authenticated() && User::is_admin();
					if ($this->data['view']) {
						$this->data['is_admin'] = true;
					}
					$this->render('ticket/validate');
					exit();
				}
			}
		}
		Request::redirect('error/index');
	}

	public function before()
	{
		if (Request::is_to([new Route('ticket','validate')])) {
			return;
		}

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