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

	public static function per_day() {
		return self::build('SELECT date(`ticket`.`created`) AS date, COUNT(`ticket`.`id`) AS count, SUM(`ticket`.`price`) AS price ' .
				'FROM `ticket` ' .
				'GROUP BY date')
			->execute();
	}

	public static function per_user() {
		return self::build('SELECT u.*, SUM(t.`price`) AS sold, COUNT(t.`id`) AS count FROM `ticket` AS t JOIN `user` AS u ' .
				'ON u.`id` = t.`seller` '.
				'GROUP BY u.id  ')
			->execute();
	}

	public static function get_price_sum(){
		return self::select('SUM(price) AS sum')
			->execute()[0]->sum;
	}

	public static function get_price_sum_from($lot_id){
		return self::select('SUM(price) AS sum')
			->where(['lot' => $lot_id])
			->execute()[0]->sum;
	}

	public static function get_count() {
		return self::select('COUNT(*) AS count')
			->execute()[0]->count;
	}
}