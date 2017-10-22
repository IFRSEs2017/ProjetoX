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
		return date('d/m/Y', $timestamp);
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

	public static function string_validation($string){
		if(strlen($string) > 2){
			if (preg_match('/^[A-Za-z0-9\s]*$/', $string)) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}


	}

	public static function email_validation($email){
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}else{
			return false;
		}
	}

	public static function cpf_validation($cpf){
		if(strlen($cpf) != 11 ){
			return false;
		}else{
			if(preg_match('/^[0-9]*$/', $cpf)){
				return true;
			}else{
				return false;
			}
		}
	}

	public static function rg_validation($rg){
		if(preg_match('/^[0-9]*$/', $rg)){
			return true;
		}else{
			return false;
		}
	}

	public static function number_validation($number){
		if(preg_match('/^[0-9]{1,4}$/', $number)){
			return true;
		}else{
			return false;
		}
	}

	public static function value_validation($value){
		if(preg_match('/^[0-9]{2}?|(,|.)[0-9]{2}/', $value)){
			return true;
		}else{
			return false;
		}
	}

	public static function date_validation($date){
		if(preg_match('/^[0-9]{2}\-[0-9]{2}\-[0-9]{4}/', $date)){
			return true;
		}else{
			return false;
		}
	}

	public static function date_ajust($date){
		$data = explode("-",$date);
		$d=mktime(0,00,00,$data[1], $data[0], $data[2]);
		return date("Y-m-d h:i:s" , $d);
	}

	public static function date_reajust($date){
		$data = explode(" ",$date);
		$d = explode("-", $data[0]);
		return $d[2] . "-" . $d[1] . "-" . $d[0];
	}
	
}