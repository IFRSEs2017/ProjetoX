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

	public static function select_valid_lot(){
		$lot = self::build('SELECT l.* FROM `lot`
			AS l WHERE l.start < CURDATE()
			AND l.end > CURDATE()
			AND (SELECT COUNT(*)
				FROM `ticket`
				AS t WHERE t.lot = l.id)
			< l.amount
			ORDER BY l.start 
			ASC LIMIT 1')->execute();
		return ($lot ? $lot[0] : null);
	}

	public static function get_lot_number($id){
		$lots = self::select('id')->order_by(['start' => 'ASC'])->execute();
		return array_search($id, $lots) + 1;
	}


}