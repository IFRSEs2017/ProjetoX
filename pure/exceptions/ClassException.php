<?php
namespace Pure\Exceptions;
use Pure\Bases\Exception;

/**
 * Exception de classe não encontrada
 *
 * Exception disparada quando não é possivel encontrar o arquivo 
 * ou executar determinado método de uma classe
 * gerada em tempo de execução.
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class ClassException extends Exception
{

	/**
	 * Construtor
	 * @param string $message relatorio do erro
	 */
	public function __construct($message)
	{
		parent::__construct('Falha ao carregar classe: ' . $message);
	}

}