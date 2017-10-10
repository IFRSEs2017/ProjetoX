<?php
namespace Pure\Bases;

/**
 * Classe básica de Exception do Framework
 *
 * Serve como base para outras exceptions lançadas ao longo
 * da execução do programa.
 *
 * @abstract
 * @version 1.0
 * @author Equipe Projeto X
 */
abstract class Exception extends \Exception
{
	/**
	 * Construtor da exception.
	 * Chama método pai.
	 * @param string $message
	 * @param int $code
	 * @param \Throwable $previous
	 */
	public function __construct($message = '', $code = 0, \Throwable $previous = NULL)
	{
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Representação textual da exception lançada.
	 * @return string mensagem
	 */
	public function __toString()
	{
		return __CLASS__ . ': ' . $this->message . ' ';
	}

}