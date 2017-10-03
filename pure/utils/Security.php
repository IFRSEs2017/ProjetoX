<?php
namespace Pure\Utils;

/**
 * Classe de segurança
 *
 * Responsável pelas funções básicas de segurança do framework
 * Validação de formulários e técnicas contra invações, injections e spoofings
 *
 * @todo
 * @version 1.0
 * @author Equipe Projeto X
 */
class Security
{
	private $session_name = null;
	private static $instance = null;

	/**
	 * Método construtor
	 *
	 * @access privado para proibir novas instances.
	 * @internal função de uso interno
	 */
	private function __construct()
	{
	}

	private function __clone(){}
	private function __wakeup(){}

	/**
	 * Recupera a única instance disponível do objeto
	 *
	 * @see Singleton
	 * @link https://pt.wikipedia.org/wiki/Singleton
	 * @return Security
	 */
	public static function get_instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new Security();
		}
		return self::$instance;
	}

	/**
	 * Gera nome de sessão individual para cada usuário
	 * Camada de segurança necessária para evitar roubos de session
	 * relacionados ao nome de padrão do cookie.
	 *
	 * @see PHPSESSID
	 * @return string
	 */
	public function session_name()
	{
		if ($this->session_name === null)
		{
			$name = new Hash();
			$this->session_name = $name->generate(
				'CustomSession' .
				$_SERVER['REMOTE_ADDR'] .
				$_SERVER['HTTP_USER_AGENT']
			)->get_hash();
		}
		return $this->session_name;
	}

}