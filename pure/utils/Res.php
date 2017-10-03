<?php
namespace Pure\Utils;

/**
 * Arquivo de recursos
 * Resposável por recuperar strings e arrays de determinado arquivo XML
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Res
{
	// raw do xml
	private static $xml;
	// dados em cache
	private static $data = [];

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

	/**
	 * Recuperar string do arquivo "app/assets/strings/pt-BR.xml"
	 * @param mixed $name nome da variavel
	 * @deprecated mixed $from grupo
	 * @return string valor
	 */
	public static function str($name, $from = 'items') {
		if(self::$xml === null)
		{
			self::prepare($name, $from);
		}
		//var_dump(self::$data[$from][0]);
		foreach(self::$data[$from][0] as $item) {
			if($item['name'] == $name) {
				return $item['value']->__toString();
			}
		}
		return '';
	}

	/**
	 * Recuperar array do arquivo "app/assets/strings/pt-BR.xml"
	 * @param mixed $name nome da variavel
	 * @deprecated mixed $from grupo
	 * @return array valor
	 */
	public static function arr($name, $from = 'items') {
		if(self::$xml === null)
		{
			self::prepare($name, $from);
		}
		foreach(self::$data[$from][0] as $item) {
			if($item['name'] == $name) {
				$array = [];
				foreach($item->string as $subitem) {
					$att = $subitem->attributes();
					$array[$att['name']->__toString()] = $att['value']->__toString();
				}
				return $array;
			}
		}
		return [];
	}

	/**
	 * Método interno de preparação
	 * @todo i18n
	 * @param mixed $name
	 * @param mixed $from
	 */
	private static function prepare($name, $from) {
		//@TODO: multilang
		$str = file_get_contents(BASE_PATH . 'app/assets/strings/pt-BR.xml');
		self::$xml = new \SimpleXMLElement($str);
		if(!isset(self::$data[$from])) {
			self::$data[$from] = self::$xml->xpath($from);
		}
	}

}