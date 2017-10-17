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
class Lot extends Model
{
	public $amount;
	public $start;
	public $end;
	public $valuation;
	public $creator;
	public $created;
	public $is_activated;
}