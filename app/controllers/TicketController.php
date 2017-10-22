<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;
use Pure\Utils\Session;
use App\Models\User;
use Pure\Utils\Hash;
use Pure\Utils\DynamicHtml;
use App\Models\Ticket;
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

	public function list_action(){
		/*
		include(BASE_PATH . 'app/utils/phpqrcode/qrlib.php');
		$word = Hash::random_word(64);
		$hash = new Ticket($word);
		$code = DynamicHtml::link_to('ticket/validation&t=' . $word . '&u=' . $this->session->get('uinfo')->id);
		\QRcode::png($code, BASE_PATH . 'app/assets/images/' . $hash->password . '.png', QR_ECLEVEL_L, 10);*/
	}

	public function insert_action(){
		// Insere pontos de vendas

	}

	public function delete_action(){
		// Exclui pontos de vendas
		echo 'Exclui';
	}

	public function update_action(){
		// Atualiza pontos de vendas
		echo 'Atualiza';
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
		//Request::redirect('error/index');
	}
}