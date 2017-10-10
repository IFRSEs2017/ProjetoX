<?php
namespace Pure\Exceptions;
use Pure\Bases\Exception;

/**
 * Exception de Rota
 *
 * Exception disparada quando não é possivel recuperar uma rota
 * válida digitada no navegador pelo usuário ($_GET['PurePage']), 
 * da rota padrão 'DefaultRoute' (@see App\Configs\Config) ou
 * da rota padrão para erros 'DefaultErrorRoute' (@see App\Configs\Config).
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class RouteException extends Exception
{

	/**
	 * Construtor 
	 * @param string $message relatorio do erro
	 */
	public function __construct($message)
	{
		parent::__construct('Rota inválida ou não existente: ' . $message);
	}

}