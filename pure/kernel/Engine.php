<?php
namespace Pure\Kernel;
use Pure\Routes\Route;
use Pure\Routes\UrlManager;
use Pure\Bases\Controller;
use Pure\Exceptions\ClassException;

/**
 * Motor de carregamento de páginas do Framework
 *
 * Classe principal do Framework, é a responsável por
 * carregar o controller, executar a action e lidar com informações
 * básicas de rotas manipulando a classe UrlManager.
 * É caracterizada por ser única no contexto, sendo acessivel
 * utilizando o método estático get_intance.
 *
 * @see Pure\Routes\UrlManager
 * @version 1.1
 * @author Equipe Projeto X
 */
class Engine
{
	private static $instance = null;
	private $manager;

	/**
	 * Método construtor
	 *
	 * @access privado para proibir novas instances.
	 * @internal função de uso interno
	 */
	private function __construct()
	{
		$this->manager = UrlManager::get_instance();
	}

	private function __clone(){}
	private function __wakeup(){}

	/**
	 * Recupera a única instance disponível do objeto
	 *
	 * @see Singleton
	 * @link https://pt.wikipedia.org/wiki/Singleton
	 * @return Engine
	 */
	public static function get_instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new Engine();
		}
		return self::$instance;
	}

	/**
	 * Inicia a aplicação recuperando a URL requisitada,
	 * gerando uma rota válida com ela e encaminhando para
	 * a construção do controller
	 */
	public function execute()
	{
		$route = $this->manager->get_route();
		$this->load_route($route);
	}

	/**
	 * Carrega controller na mémoria e executa a action
	 * de determinada rota
	 *
	 * @param Route $route rota a ser carregada
	 */
	public function load_route(Route $route)
	{
		if (!$this->manager->route_exists($route))
		{
			$route = $this->manager->get_error_route();
		}
		$controller = $this->load_controller($route);
		$this->load_action($controller, $route);
	}

	/**
	 * Gera uma instance, se possivel, do controller desejado pela rota.
	 *
	 * @param Route $route rota que irá originar o controller
	 * @throws ClassException se não for possivel achar a classe desejada.
	 * @return Controller instance de controller
	 */
	private function load_controller(Route $route)
	{
		$class = 'app\\controllers\\' . $route->get_controller();
		if (!class_exists($class))
		{
			throw new ClassException('O controller ' . $class . ' não existe');
		}
		return new $class();
	}

	/**
	 * Acessa metodo action, se possivel, desejado pela rota no controller
	 *
	 * @param Controller $controller instace de um controller
	 * @param Route $route rota que irá originar o método action
	 * @throws ClassException se não for possivel achar o método desejado
	 */
	private function load_action(Controller $controller, Route $route)
	{
		$action = $route->get_action();
		$param = $route->get_param();

		if (!method_exists($controller, $action))
		{
			throw new ClassException('O método ' . $action . ' não está presente no controller ');
		}
		$controller->before();
		$controller->$action($param);
		$controller->after();
		exit();
	}

}