<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Db\Database;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Hash;
use Pure\Utils\DynamicHtml;
use Pure\Utils\Res;
use App\Models\User;
use App\Models\Lot;
use App\Models\Ticket;
use App\Utils\Helpers;
use App\Utils\Mailer;

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

	/**
	 * Realiza a venda de um ingresso,
	 * criando uma chave de segurança que será
	 * enviada por e-mail para o comprador
	 *
	 * Essa chave deverá permanecer em mãos
	 * do usuário até o dia da festa, como comprovante
	 * de compra e validação
	 */
	public function sell_action(){
		if (Request::is_POST()) {
			$errors = [];
			$data = $this->params->unpack('POST', ['form_name','form_cpf','form_email']);
			$ticket_secret = Hash::random_word(64);
			$ticket = new Ticket($ticket_secret);
			$lot = Lot::select_valid_lot();
			// Caso exista um lote elegivel para venda
			// e os dados enviados por parametros estejam corretos
			if ($lot && $data) {
				$ticket->lot = $lot->id;
				$ticket->price = $lot->valuation;
				$ticket->seller = $this->session->get('uinfo')->id;
				// Valida os dados enviados por formulário
				Helpers::string_validation($data['form_name']) ?
					$ticket->owner_name = $data['form_name'] :
					array_push($errors, 'Nome inválido');
				Helpers::email_validation($data['form_email']) ?
					$ticket->owner_email = $data['form_email'] :
					array_push($errors, 'E-mail inválido');
				Helpers::cpf_validation($data['form_cpf']) ?
					$ticket->owner_cpf = $data['form_cpf'] :
					array_push($errors, 'CPF inválido');
				$other = Ticket::find(['owner_cpf' => $data['form_cpf']]);
				if ($other) {
					array_push($errors,
						'Já foi vendido um ingresso para o CPF ' .
						$data['form_cpf'] . '.');
				}
			}
			else {
				array_push($errors, 'Lote inválido ou dados incorretos');
			}
			// Caso existe algum erro nos dados
			// informados ou com o lote
			if(count($errors) > 0){
				$this->data['errors'] = $errors;
				$this->data['title'] = "Ops!";
				$this->data['class'] = "class = 'alert alert-danger alert-dismissible fade show'";
				$this->data['user'] = $this->session->get('uinfo')->id;
			}
			// Registra a compra em banco de dado
			// e envia um e-mail para o usuário
			// com seu cartão de acesso para a festa
			else {
				$db = Database::get_instance();
				try {
					$db->begin();
					$id = Ticket::save($ticket);
					$code = $this->create_qrcode($ticket_secret, $ticket->password, $id);
					// Envia e-mail para o usuário
					if(PURE_ENV != 'development') {
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
					$this->data['message'] = 'O ingresso foi vendido com sucesso!' .
						'<br> Confira seu e-mail para confirmar a venda:';
					$this->data['email'] = $ticket->owner_email;
					$this->data['qrcode'] = $code;
					$this->render('seller/sold');
					$db->commit();
					exit();
					// Caso não seja possível
					// salvar em banco ou enviar o e-mail
					// de confirmação, realiza rollback
				}
				catch(\Exception $e) {
					$db->rollback();
					Mailer::bug_report($e);
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

	/**
	 * Preenche parametros de lote nos dados
	 * que irão para a view
	 * @param mixed $lot lote atual
	 */
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

	/**
	 * Gera um QRCode que será salvo no servidor
	 * e posteriormente enviado por e-mail para o usuário
	 *
	 * @param mixed $secret palavra secreta que define o ingresso
	 * @param mixed $hash palavra encriptada com um segredo
	 * @param mixed $id id do ticket
	 * @return string
	 */
	private function create_qrcode($secret, $hash, $id){
		include(BASE_PATH . 'app/utils/phpqrcode/qrlib.php');
		$url = DynamicHtml::link_to('ticket/validate&t=' . $secret . '&i=' . $id);
		$filename = BASE_PATH . 'app/assets/images/' . $hash . '.png';
		$url = DynamicHtml::link_to('app/assets/images/' . $hash . '.png');
		\QRcode::png($url, $filename, QR_ECLEVEL_L, 10);
		return $url;
	}

	/**
	 * Verifica se usuário está logado
	 *
	 * Somente vendedores poderão visualizar as opções
	 * de venda de ingressos
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
