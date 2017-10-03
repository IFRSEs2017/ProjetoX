<?php
namespace Pure\Utils;
use Pure\Utils\Security;

/**
 * Classe gerenciadora de dados da variavel $_SESSION
 *
 * Nela estão algumas funcionalidades para facilitar a utilização de sessões
 * bem como aprimoramentos de segurança.
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Session
{
	private static $instance = null;

	/**
	 * Método construtor
	 *
	 * @access privado para proibir novas instances.
	 * @internal função de uso interno
	 */
	private function __construct() {}

	private function __clone(){}
	private function __wakeup(){}

	/**
	 * Recupera a única instance disponível do objeto
	 * Inicia a session juntamente com a primeira geração do objeto
	 * A session possui um nome personalizado vinculado ao usuário
	 * gerado pela classe de segurança (Pure\Uril\Security) para impedir roubos de sessão .
	 *
	 * @see Pure\Utils\Security
	 * @see Singleton
	 * @link https://pt.wikipedia.org/wiki/Singleton
	 * @return Session
	 */
	public static function get_instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new Session();
			$security = Security::get_instance();
			session_name($security->session_name());
			session_cache_expire(10);
			session_start();
		}
		return self::$instance;
	}

	/**
	 * Grava determinada variavel dentro das variaveis de session
	 *
	 * @param string $name nome da variavel
	 * @param mixed $value variavel
	 */
	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
	}

	/**
	 * Limpa determinada variavel dentro das variaveis de session
	 *
	 * @param string $name nome da variavel
	 * @return boolean a variavel existia?
	 */
	public function wipe($name)
	{
		if (isset($_SESSION[$name]))
		{
			unset($_SESSION[$name]);
			return true;
		}
		return false;
	}

	/**
	 * Responde quanto a existencia ou não de uma variavel na session
	 *
	 * @param string $name nome da variavel
	 * @return boolean resposta
	 */
	public function contains($name)
	{
		return isset($_SESSION[$name]);
	}

	/**
	 * Recupera valor da variavel de session caso ela exista
	 *
	 * @param string $name nome da variavel
	 * @return mixed variavel
	 */
	public function get($name)
	{
		if (isset($_SESSION[$name]))
		{
			return $_SESSION[$name];
		}
		return false;
	}

	/**
	 * Finaliza uma session
	 */
	public function destroy()
	{
		session_destroy();
	}

}