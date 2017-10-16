<?php
namespace App\Models;
use Pure\Bases\Model;
use App\Models\Permission;
use Pure\Utils\Session;
/**
 * Representa um usuário na camada de Modelagem,
 * ou seu repositório
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class User extends Model
{
	public $email;
	public $password;
	public $name;
	//public $rg;
	public $cpf;
	public $is_admin;
	public $is_actived;

	public static function is_admin(){
		$session = Session::get_instance();
		$user = $session->get('uinfo');
		if ($user){
			$dbuser = User::find(['id' => $user->id]);
			if ($dbuser) {
				return $dbuser->is_admin;
			}
		}
		return false;
	}

	public function __construct()
	{
	}

}