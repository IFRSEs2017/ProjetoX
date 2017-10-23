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

	/**
	 * Página para erros desconhecidos, na maioria das vezes
	 * provenientes de tratamentos de exceptions disparadas pelos controllers
	 * durante o transacoes no banco de dados
	 */
	public function unknown_action(){
		$this->render('unknown', 'default');
	}
}