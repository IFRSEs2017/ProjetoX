<?php
namespace Pure\Exceptions;
use Pure\Bases\Exception;

/**
 * Exception de view
 *
 * Exception disparada quando não é possivel incluir determinado script ao
 * contexto de execução da view.
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class ViewException extends Exception
{

	/**
	 * Construtor
	 * @param string $message relatorio do erro
	 */
	public function __construct($message)
	{
		parent::__construct('Falha ao carregar script da View: ' . $message);
	}

}