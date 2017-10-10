<?php
namespace Pure\Exceptions;
use Pure\Bases\Exception;

/**
 * Exception de banco de dados
 *
 * Exception disparada quando não é possivel configurar
 * uma conexão válida com o banco de dados por meio das configurações
 * presentes na classe Config.
 *
 * @see App\Configs\Config
 * @version 1.0
 * @author Equipe Projeto X
 */
class DatabaseException extends Exception
{

	/**
	 * Construtor
	 * @param string $message relatorio do erro
	 */
	public function __construct($message)
	{
		parent::__construct('Falha no banco de dados: ' . $message);
	}

}