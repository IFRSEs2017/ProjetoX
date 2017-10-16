<?php
namespace Pure\Utils;
use Pure\Routes\UrlManager;

/**
 * Classe de criação de HTML dinâmico
 *
 * Responsável pelo gerar código HTML dinâmicamente na View
 * utilizando métodos estáticos.
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class DynamicHtml
{

	/**
	 * Gera uma tag HTML que faz o link entre a página e um arquivo CSS
	 *
	 * @param string $path nome do arquivo em app\assets\stylesheets
	 * @return string código HTML
	 */
	public static function link_css($path)
	{
		return '<link href="' . UrlManager::get_instance()->get_base_url() .
			'app/assets/stylesheets/' . $path .
			'" rel="stylesheet">';
	}

	/**
	 * Gera uma tag HTML que faz o link entre a página e um arquivo favicon
	 *
	 * @param string $path nome do arquivo em app\assets\favicon
	 * @return string código HTML
	 */
	public static function link_favicon($path)
	{
		return '<link rel="shortcut icon" href="' .
			UrlManager::get_instance()->get_base_url() .
			'app/assets/favicon/favicon.ico" type="image/x-icon" />';
	}

	/**
	 * Gera uma tag HTML que insere uma imagem na página
	 *
	 * @param string $path nome do imagem em app\assets\images
	 * @return string código HTML
	 */
	public static function img($name, $classes = [])
	{
		return '<img ' . DynamicHtml::generate_classes($classes) .
			' src="' . UrlManager::get_instance()->get_base_url() .
			'app/assets/images/' . $name . '">';
	}

	/**
	 * Gera um link reativo para a URL digitada pelo usuário
	 * Exemplo:
	 * String: 'site/index'
	 * Resultado: 'http://sitename/site/index'
	 *
	 * @param string $path caminho relativo
	 * @return string url final
	 */
	public static function link_to($path, $get = [])
	{
		return UrlManager::get_instance()->get_base_url() . $path;
	}

	/**
	 * Gera uma tag HTML que faz o link entre a página e um arquivo JS
	 *
	 * @param string $path nome do arquivo em app\assets\scripts
	 * @return string código HTML
	 */
	public static function link_script($path)
	{
		return '<script src="' . UrlManager::get_instance()->get_base_url() .
			'app/assets/scripts/' . $path .
			'"></script>';
	}

	/**
	 * Clase de uso interno para geração de HTML
	 * @param array $classes ['key' => 'value']
	 * @return string conteúdo HTML
	 */
	private static function generate_classes($classes)
	{
		$string = '';
		foreach($classes as $classname => $content)
		{
			$string .= $classname . '="' . $content . '" ';
		}
		return $string;
	}

}