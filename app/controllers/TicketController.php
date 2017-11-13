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

	public function list_action()
	{
		$tickets = Ticket::select()
			->order_by(['created' => 'DESC'])
			->execute();
		$this->data['list'] = $tickets;
		$this->render('ticket/list');

	}

	public function insert_action(){}

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

				// Caso exista algum erro no formulário
				if(count($errors) > 0){
					$this->data['errors'] = $errors;
					$this->data['title'] = "Ops!";
					$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
					$this->data['user'] = $user_db;
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
		//Request::redirect('error/index');
	}
}
