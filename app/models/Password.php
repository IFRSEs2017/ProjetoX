<?php
namespace App\Models;
use App\Models\Secret;

/**
 * Representa uma senha na camada de Modelagem,
 * ou seu repositório
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Password extends Secret
{
	public function __construct($secret)
	{
		parent::__construct($secret);
	}
}