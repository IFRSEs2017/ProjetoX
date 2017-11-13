<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use App\Models\Ticket;
use App\Utils\Helpers;
use Pure\Db\Database;
use App\Utils\Mailer;


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
		if (Request::is_POST()){
			$data = $this->params->unpack('POST', ['search']);
			
			if((Helpers::string_validation($data['search'])) && (!Helpers::rg_validation($data['search'])))
			{
				$ticket_db = Ticket::select()
				->where(['owner_name' => $data['search']])
				->execute();
				if($ticket_db)
				{
					$this->data['search_result'] = $ticket_db;
				}
				else
				{
					$this->data['not_found_result'] = $ticket_db;									
				}
			}
			else if(Helpers::email_validation($data['search']))
			{
				
				$ticket_db = Ticket::select()
					->where(['owner_email' => $data['search']])
					->execute();
					if($ticket_db)
					{
						$this->data['search_result'] = $ticket_db;
					}
					else
					{
						$this->data['not_found_result'] = $ticket_db;									
					}
			}
			else if(Helpers::cpf_validation($data['search']))
			{
				
				$ticket_db = Ticket::select()
					->where(['owner_cpf' => $data['search']])
					->execute();
					if($ticket_db)
					{
						$this->data['search_result'] = $ticket_db;
					}
					else
					{
						$this->data['not_found_result'] = $ticket_db;									
					}
			}
			
		}
		$tickets = Ticket::select()
			->order_by(['created' => 'ASC'])
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

	/**
	 * Controller ainda não utilizado
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