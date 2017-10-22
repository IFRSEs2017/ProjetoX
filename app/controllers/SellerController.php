<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Session;
use App\Models\User;
use App\Models\Lot;
use App\Models\Ticket;
use Pure\Utils\Hash;
use App\Utils\Helpers;
use Pure\Utils\DynamicHtml;

/**
 * Controller principal
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class SellerController extends Controller
{
	/**
	 * Método carregado ao acessar a rota
	 * ProjetoX/site/index e ProjetoX/
	 *
	 * Reponsável por carregar a página principal
	 */
	public function index_action()
	{
		$this->render('seller/dashboard');
	}

	public function sell_action(){
		if (Request::is_POST()) {
			$errors = [];
			$data = $this->params->unpack('POST', ['form_name','form_cpf','form_email']);
			$ticket_secret = Hash::random_word(64);
			$ticket = new Ticket($ticket_secret);
			$lot = Lot::select_valid_lot();
			if ($lot) {
				$ticket->lot = $lot->id;
				$ticket->price = $lot->valuation;
				$ticket->seller = $this->session->get('uinfo')->id;
				Helpers::string_validation($data['form_name']) ? $ticket->owner_name = $data['form_name'] : array_push($errors, 'Nome inválido');
				Helpers::email_validation($data['form_email']) ? $ticket->owner_email = $data['form_email'] : array_push($errors, 'E-mail inválido');
				Helpers::cpf_validation($data['form_cpf']) ? $ticket->owner_cpf = $data['form_cpf'] : array_push($errors, 'CPF inválido');
				$other = Ticket::find(['owner_cpf' => $data['form_cpf']]);
				if ($other) {
					array_push($errors, 'Já foi vendido um ingresso para o CPF ' . $data['form_cpf'] . '.');
				}
			} else {
				array_push($errors, 'Lote inválido');
			}
			if(count($errors) > 0){
				$this->data['errors'] = $errors;
				$this->data['title'] = "Ops!";
				$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
				$this->data['user'] = $this->session->get('uinfo')->id;
			}else{
				try {
					$id = Ticket::save($ticket);
					$code = $this->create_qrcode($ticket_secret, $ticket->password);
					if(PURE_ENV != 'development') {
						echo '<br><br><br>' . DynamicHtml::link_to('sell/validate&k=' . $ticket_secret . '&u=' . $id);
					} else {
						//Mailer::send_password_reset($user->name, $user->email, Res::str('app_title') . ' - Redefinir a senha', DynamicHtml::link_to('login/validate_reset&k=' . $word . '&u=' . $user->id));
					}
					$this->data['message'] = 'O ingresso foi vendido com sucesso! <br> Confira seu e-mail para confirmar a venda:';
					$this->data['email'] = $ticket->owner_email;
					$this->data['qrcode'] = $code;
					$this->render('seller/sold');
					exit();
				} catch(\Exception $e)
				{
					//Mailer::send_bug();
					Request::redirect('error/unknown');
				}
			}
			$this->set_lot_data($lot);
			$this->render('seller/sell');
			exit();
		} else {
			$lot = Lot::select_valid_lot();
			$this->set_lot_data($lot);
			$this->render('seller/sell');
		}
	}

	private function set_lot_data($lot)
	{
		if ($lot) {
			$sold_tickets = Ticket::count($lot->id);
			$this->data['lot_number'] = Lot::get_lot_number($lot->id);
			$this->data['lot'] = $lot;
			$this->data['remain'] = $lot->amount - $sold_tickets;
		} else {
			$this->data['title'] = "Ops! Não há lotes disponíveis.";
			$this->data['class'] = "class = 'alert alert-danger fade show'";
			$this->data['message'] = 'Nenhum ingresso está disponível para venda no momento.';
			$this->render('message');
			exit();
		}
	}


	private function create_qrcode($secret, $hash){
		include(BASE_PATH . 'app/utils/phpqrcode/qrlib.php');
		$url = DynamicHtml::link_to('ticket/validation&t=' . $secret . '&u=' . $this->session->get('uinfo')->id);
		$filename = BASE_PATH . 'app/assets/images/' . $hash . '.png';
		$url = DynamicHtml::link_to('app/assets/images/' . $hash . '.png');
		\QRcode::png($url, $filename, QR_ECLEVEL_L, 10);
		return $url;
	}

	/**
	 * Verifica se usuário está logado
	 */
	public function before()
	{
		$this->data['page_name'] = 'Vendedor';

		if (!Auth::is_authenticated())
		{
			Request::redirect('login/do');
		}

		if(User::is_admin()){
			Request::redirect('admin/index');
		}
		$this->data['is_admin'] = false;
	}


}
