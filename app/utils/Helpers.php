<?php
namespace App\Utils;
use Pure\Utils\DynamicHtml;

/**
 * Classe de apoio para o desenvolvedor no desenvolvimento
 * centraliza métodos práticos que podem ser chamados a qualquer momento
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Helpers
{
	/**
	 * Cria código HTML referente a um breadcrumb boostrap
	 *
	 * @param mixed $self objeto atual da página
	 * @param mixed $crumbs caminho até esse objeto
	 * @return string HTML
	 */
	public static function breadcrumb($self, $crumbs)
	{
		$html = '<div class="row">';
		$html .= '<div class="col-md-12 content-category">';
		$html .= '<ol class="breadcrumb">';
		foreach($crumbs as $name => $url)
		{
			$html .= '<li><a href="' . DynamicHtml::link_to($url) . '">' . $name . '</a></li>';
		}
		$html .= '<li class="active">' . $self . '</li>';
		$html .= '</ol></div></div>';
		return $html;
	}

	/**
	 * Formata data e hora para padrão de exibição
	 * @param mixed $time data padrão americano
	 * @return string data
	 */
	public static function date_format($time)
	{
		$timestamp = strtotime($time);
		return date('G:i - d/m/Y', $timestamp);
	}

	/**
	 * Gera um elemento HTML de paginação
	 * @param mixed $limit limite de itens por página
	 * @param mixed $count contagem de itens totais
	 * @param mixed $page número de página
	 * @return string elemento DOM
	 */
	public static function pagination($limit, $count, $page)
	{
		$pages = ($count % $limit) ? intval($count / $limit) + 1 : $count / $limit;
		$html = '<ul class="pagination text-center">';
		for($i = 1; $i <= $pages; $i++){
			if($i == $page){
				$html .= '<li class="page active"><a href="#">' . $i .'</a></li>';
			} else {
			$html .= '<li class="page"><a href="">' . $i . '</a></li>';
			}
		}
		$html .= '</ul>';
		return $html;
	}

	public static function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}