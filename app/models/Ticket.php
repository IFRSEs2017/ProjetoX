<?php
namespace App\Models;
use Pure\Bases\Model;

/**
 * Representa uma senha na camada de Modelagem,
 * ou seu repositório
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Ticket extends Secret
{
	public $lot;
	public $seller;
	public $price;
	public $owner_name;
	public $owner_email;
	public $owner_cpf;

	public function __construct($secret)
	{
		parent::__construct($secret);
	}
}