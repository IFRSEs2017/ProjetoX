<?php
namespace Pure\Utils;

/**
 * Classe gerenciadora de autenticação de usuários
 *
 * Responsável pelo gerenciamento especifico de dados de session utilizados como autenticadores
 * Define o usuário como autenticado, verifica autenticidade de session e outras funcionalidades
 * relacionadas a autenticação.
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Auth
{
	/**
	 * Verifica se usuário está autenticado e é dono de uma session válida
	 * A verificação de session é feita através da comparação entrei os dados da session do usuário
	 * e da hash gerada pela classe de segurança.
	 *
	 * @see Pure\Utils\Security
	 * @return boolean resposta em relação a validade do login
	 */
	public static function is_authenticated()
	{
		$session = Session::get_instance();
		$security = Security::get_instance();
		return ($session->get('uid') !== false && $session->get('session_owner') === $security->session_name());
 	}

	/**
	 * Realiza a gravação de session que define um usuário como autenticado ou não
	 * @param integer $user_id identificador único do usuário
	 * @param mixed $user_info informações de session que se deseja manter sobre o usuário
	 */
	public static function authenticate($user_id, $user_info = null)
	{
		$session = Session::get_instance();
		$security = Security::get_instance();
		$session->set('uid', $user_id);
		$session->set('uinfo', $user_info);
		$session->set('session_owner', $security->session_name());
	}

	/**
	 * Finaliza a sessao e faz logout do usuário na session.
	 */
	public static function revoke()
	{
		$session = Session::get_instance();
		$session->wipe('uid');
		$session->wipe('uinfo');
		$session->wipe('session_owner');
		$session->destroy();
	}

}