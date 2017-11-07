<?php
namespace App\Models;

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

	public static function count($lot_id)
	{
		return self::select('COUNT(*) AS count')
				->where(['lot' => $lot_id])
				->execute()[0]
				->count;
	}


	public static function last_month_count() {
		return self::build('SELECT date(`ticket`.`created`) AS y, COUNT(`ticket`.`id`) AS x ' .
				'FROM `ticket` ' .
				'WHERE `ticket`.`created` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() ' .
				'GROUP BY y')->execute();
	}

	public static function last_month_fature() {
		return self::build('SELECT date(`ticket`.`created`) AS y, SUM(`ticket`.`price`) AS x ' .
				'FROM `ticket` ' .
				'WHERE `ticket`.`created` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() ' .
				'GROUP BY y')->execute();
	}

	public static function get_price_sum(){
		return self::select('SUM(price) AS sum')
			->execute()[0]->sum;
	}
}