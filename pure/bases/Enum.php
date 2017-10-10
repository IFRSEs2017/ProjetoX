<?php
namespace Pure\Bases;
use ReflectionClass;

/**
 * Classe base para enumerações
 *
 * @author Brian Cline at StackOverflow
 * @link http://stackoverflow.com/questions/254514/php-and-enumerations
 */
abstract class Enum
{

	private static $cache_array = null;

	private static function get_constants()
	{
		if (self::$cache_array == null)
		{
			self::$cache_array = [];
		}
		$called_class = get_called_class();
		if (!array_key_exists($called_class, self::$cache_array))
		{
			$reflect = new ReflectionClass($called_class);
			self::$cache_array[$called_class] = $reflect->getConstant();
		}
		return self::$cache_array[$called_class];
	}

	public static function is_valid_name($name, $strict = false)
	{
		$constants = self::get_constants();
		if ($strict)
		{
			return array_key_exists($name, $constants);
		}
		$keys = array_map('strtolower', array_keys($constants));
		return in_array(strtolower($name), $keys);
	}

	public static function is_valid_value($value, $strict = true)
	{
		$values = array_values(self::get_constants());
		return in_array($value, $values, $strict);
	}

}