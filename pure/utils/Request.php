<?php
namespace Pure\Utils;
use Pure\Kernel\Engine;
use Pure\Routes\UrlManager;
use Pure\Routes\Route;

/**
 * Gerenciador de requisições
 *
 * Trata redirecionamentos, envio de requisições e recarregamentos
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Request
{

	/**
	 * Verifica se a rota atual (@see $manager->get_route())
	 * está presente no array de rotas enviadas por parametro
	 *
	 * Em outras palavras, verifica se a rota atual pertence a lista enviada.
	 *
	 * @param Route[] $routes array de rotas
	 * @return boolean resposta
	 */
	public static function is_to($routes = [])
	{
		$manager = UrlManager::get_instance();
		$current = $manager->get_route();
		foreach($routes as $route)
		{
			if($current->equals($route))
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Redireciona o usuário para uma página dentro da própria aplicação
	 *
	 * Exemplo:
	 * - String: 'controller/action'
	 *
	 * @param Route|string $destination
	 * @return boolean resposta
	 */
	public static function redirect($destination)
	{
		if (is_string($destination))
		{
			$manager = UrlManager::get_instance();
			header('location: ' . $manager->get_base_url() . $destination);
			exit();
		}
		return false;
	}

	public static function require_ssl()
	{
		if($_SERVER["HTTPS"] != "on")
		{
			header("location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			exit();
		}
	}

	/**
	 * Redireciona o usuário para uma página externa à aplicação
	 *
	 * Exemplo:
	 * - String: 'http://google.com'
	 *
	 * @param mixed $url
	 * @return boolean resposta
	 */
	public static function redirect_to_url($url)
	{
		if (is_string($url))
		{
			header('location: ' . $url);
			exit();
		}
		return false;
	}

	public static function is_ajax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	/**
	 * @todo
	 */
	public static function reload()
	{
	}

	/**
	 * @todo
	 * @param mixed $content
	 * @param mixed $method
	 * @param mixed $header
	 */
	public static function send($content, $method, $header)
	{
	}

	/**
	 * Verifica se a requisição atual pelo usuário é POST
	 * @return boolean resposta
	 */
	public static function is_POST()
	{
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}
}