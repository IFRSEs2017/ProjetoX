<?php
namespace App\Models;
use Pure\Bases\Model;
use App\Models\Permission;
/**
 * Representa um usu�rio na camada de Modelagem,
 * ou seu reposit�rio
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class User extends Model
{
	public $email;
	public $password;
	public $name;
	public $is_admin;
	public $is_enabled;

}