<?php
namespace App\Models;
use Pure\Bases\Model;
use Pure\Utils\Hash;

/**
 * Representa uma senha na camada de Modelagem,
 * ou seu repositório
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Password extends Model
{
	public $iterations;
	public $password;
	public $salt;
	public $created;

	/**
	 * Construtor personalizado para Password
	 * @param string $password senha do usuário
	 */
	public function __construct($password)
	{
		$hash = new Hash();
		$pass = $hash->generate($password, true, true)->get_hash_secret();
		$this->password = $pass['hash'];
		$this->iterations = $pass['iterations'];
		$this->salt = $pass['salt'];
	}

	/**
	 * Realiza o processo de encriptação e verifica se a senha
	 * enviada é igual a senha cadastrada em banco de dados
	 * @param object $password senha do banco de dados
	 * @param string $secret senha para comparação
	 * @return boolean resposta
	 */
	public static function compare($password, $secret)
	{
		$new = new Hash();
		$new->generate_with($secret, $password->salt,  $password->iterations);
		return ($password->password === $new->get_hash());
	}


}