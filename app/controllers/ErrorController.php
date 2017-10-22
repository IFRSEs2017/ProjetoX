<?php
namespace App\Controllers;
use Pure\Bases\Controller;
/**
 * Controller de erro
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class ErrorController extends Controller
{
	/**
	 * Mostra uma tela de erro
	 * ProjetoX/error/index
	 *
	 * Rota de resposta para falhas
	 */
	public function index_action(){
		$this->render('error', 'default');
	}

	public function unknown_action(){
		$this->render('unknown', 'default');
	}
}