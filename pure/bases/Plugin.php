<?php
namespace Pure\Bases;
use Pure\Exceptions\ClassException;

/**
 * Classe básica para Plugins
 *
 * Deve servir como base para outros plugins, é
 * uma base para a importação de plugins para dentro do framework
 * Possibilita utilizar classes que possuem autoloaders próprios ou
 * que executam em um namespace diferente
 *
 * @abstract
 * @version 1.0
 * @author Equipe Projeto X
 */
abstract class Plugin
{

	protected $plugin;
	private $class_reference;
	private static $instance = null;

	/**
	 * Método construtor, garantindo a inclusão do namespace da base PATH do plugin
	 */
	private function __construct()
	{
		$this->self_autoloader_registration();
		$source = $this->source();
		$uppername = strtoupper($source['class']);
		if(!defined($uppername . '_BASE_PATH'))
		{
			define($uppername . '_BASE_PATH', BASE_PATH . 'app/plugins/sources/' . strtolower($source['namespace']) . '/');
		}
		$this->class_reference = '\\' . $source['namespace'] . '\\' . $source['class'];
	}

	/**
	 * Cria uma instance do objeto relacionado ao plugin
	 * @return object
	 */
	public static function create()
	{
		$class = get_called_class();
		$newest = new $class;
		$newest->plugin = new $newest->class_reference();
		return $newest;
	}

	/**
	 * Registra o autoloader do plugin
	 */
	private function self_autoloader_registration()
	{
		spl_autoload_register(
			function ($classname)
			{
				$classname = ltrim($classname, '\\');
				$filename  = '';
				$namespace = '';
				if ($lastnspos = strripos($classname, '\\'))
				{
					$namespace = substr($classname, 0, $lastnspos);
					$namespace = strtolower($namespace);
					$classname = substr($classname, $lastnspos + 1);
					$filename  = str_replace('\\', '/', $namespace) . '/';
				}
				$filename .= str_replace('_', '/', $classname) . '.php';
				$to_require = BASE_PATH . 'app/plugins/sources/' . $filename;
				if (!file_exists($to_require))
				{
					return false;
				}
				require_once($to_require);
				return true;
			}
		);
	}

	/**
	 * Chama os métodos presentes no objeto do plugin
	 * @param mixed $method nome do método
	 * @param mixed $arguments argumentos
	 * @throws \BadMethodCallException caso o método não exista
	 * @return mixed retorno do método
	 */
	public function __call($method, $arguments)
	{
		if(method_exists($this->plugin, $method))
		{
			$result = call_user_func_array(array($this->plugin, $method), $arguments);
			return $result;
		} else {
			$class = get_class($this);
			throw new \BadMethodCallException('O método ' . $method . ' não está presente em ' . $class);
		}
	}

	/**
	 * Local do plugin
	 */
	public abstract function source();

}