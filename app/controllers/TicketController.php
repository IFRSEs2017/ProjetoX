<?php
namespace App\Controllers;
use Pure\Bases\Controller;
use Pure\Utils\Request;
use Pure\Utils\Auth;

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

	public function list_action(){}

	public function insert_action(){}

	public function delete_action(){}

	public function update_action(){}

	/**
	 * Controller ainda não utilizado
	 */
	public function before()
	{
		if (!Auth::is_authenticated())
		{
			Request::redirect('login/do');
		}
		Request::redirect('error/index');
	}
}