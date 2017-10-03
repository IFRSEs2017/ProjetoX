<?php
namespace Pure\Routes;
use App\Configs\Config;
use Pure\Exceptions\RouteException;

/**
 * Gerenciador de rotas
 *
 * Responsavel por gerenciar, criar, modificar e validar
 * dados enviados por meio de uma URL.
 *
 * @version 1.1
 * @author Equipe Projeto X
 */
class UrlManager
{
	private static $instance = null;
	private $base_url;

	/**
	 * Método construtor
	 *
	 * @access privado para proibir novas instances
	 * @internal função de uso interno
	 */
	private function __construct() {
		$this->base_url = UrlManager::set_base_url();
	}

	private function __clone(){}
	private function __wakeup(){}

	/**
	 * Recupera a única instance disponível do objeto.
	 *
	 * @see Singleton
	 * @link https://pt.wikipedia.org/wiki/Singleton
	 * @return UrlManager
	 */
	public static function get_instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new UrlManager();
		}
		return self::$instance;
	}

	/**
	 * Cria uma rota a partir da URL digitada pelo usuário.
	 * Com o arquivo .htacess, as rotas são enviadas para a variavel $_GET['PurePage']
	 *
	 * @see Pure\Routes\Route
	 * @return Route rota desejada ou rota padrão
	 */
	public function get_route()
	{
		if (isset($_GET['PurePage']))
		{
			$requested = trim($_GET['PurePage']);
			$exploded_url = explode('/', $requested);
			$route = new Route(
				(isset($exploded_url[0])) ? $exploded_url[0] : '',
				(isset($exploded_url[1])) ? $exploded_url[1] : '',
				(isset($exploded_url[2])) ? $exploded_url[2] : ''
			);
			return $route;
		}
		return $this->get_default_route();
	}

	/**
	 * Recupera rota utilizada como padrão caso o usuário não defina uma.
	 *
	 * @throws RouteException se a rota não estiver configurada em App\Configs\Config::routes
	 * @return Route rota padrão
	 */
	public function get_default_route()
	{
		$routes = Config::routes();
		if (!isset($routes['DefaultRoute']))
		{
			throw new RouteException('Rota padrão não configurada em App\Configs\Config::routes.');
		}
		return $routes['DefaultRoute'];
	}

	/**
	 * Recupera rota utilizada caso a rota digitada pelo usuário
	 * não seja válida ou encontrada.
	 *
	 * @throws RouteException se a rota não estiver configurada em App\Configs\Config::routes()
	 * @return Route rota padrão para erros
	 */
	public function get_error_route()
	{
		$routes = Config::routes();
		if (!isset($routes['DefaultErrorRoute']))
		{
			throw new RouteException('Rota padrão para erros não configurada em App\Configs\Config::routes.');
		}
		return $routes['DefaultErrorRoute'];
	}

	/**
	 * Gera a URL base do serviço acessado pelo usuário
	 * @return string url base da aplicação
	 */
	private static function set_base_url()
	{
		$path = $_SERVER['PHP_SELF'];
		$info = pathinfo($path);
		$hostname = $_SERVER['HTTP_HOST'];
		$protocol = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
		return $protocol . $hostname . $info['dirname'] . '/';
	}

	/**
	 * Recupera a URL base do serviço acessado pelo usuário
	 * @return string url base da aplicação
	 */
	public function get_base_url()
	{
		return $this->base_url;
	}

	/**
	 * Verifica se a rota enviada por parametro é válida.
	 *
	 * @param Route $route a ser verificada
	 * @return boolean resposta
	 */
	public function route_exists(Route $route)
	{
		$class = 'app\\controllers\\' . $route->get_controller();
		$filename = 'app/controllers/' . $route->get_controller() . '.php';
		$action = $route->get_action();
		return (is_file($filename) && class_exists($class) && method_exists($class, $action));
	}

}