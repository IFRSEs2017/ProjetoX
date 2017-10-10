<?php
namespace Pure\Utils;

/**
 * Gerenciador de parametros GET e POST
 *
 * Responsável por centralizar e faciltiar a utilização dos valores das variaveis $_GET e $_POST
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class Params
{
	private static $instance = null;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

	/**
	 * Método construtor
	 *
	 * @access privado para proibir novas instances.
	 * @internal função de uso interno
	 */
    public static function get_instance()
	{
        if (self::$instance === null)
		{
            self::$instance = new Params();
        }
        return self::$instance;
    }

    /**
     * Recupera informação em determinada key da variavel $_POST
     * @param string $key nome da key
     * @return false|mixed conteudo ou false (se não existir)
     */
    public function from_POST($key)
	{
        if (isset($_POST[$key]))
		{
            return $_POST[$key];
        }
        return false;
    }

    /**
	 * Recupera informação em determinada key da variavel $_GET
	 * @param string $key nome da key
	 * @return false|mixed conteudo ou false (se não existir)
	 */
    public function from_GET($key)
	{
        if (isset($_GET[$key]))
		{
            return $_GET[$key];
        }
        return false;
    }

	/**
	 * Recupera informação em determinada key da variavel $_FILES
	 * @param string $key nome da key
	 * @return false|mixed conteudo ou false (se não existir)
	 */
    public function from_FILES($key)
	{
        if (isset($_FILES[$key]))
		{
            return $_FILES[$key];
        }
        return false;
    }

	public function from_SERVER($key)
	{
        if (isset($_SERVER[$key]))
		{
            return $_SERVER[$key];
        }
        return false;
    }

    /**
	 * Recupera informação em determinada key das variaveis $_POST ou $_GET
	 * @param string $key nome da key
	 * @return false|mixed conteudo ou false (se não existir)
	 */
    public function get($key)
	{
        if (isset($_GET[$key]))
		{
            return $_GET[$key];
        }
		else if (isset($_POST[$key]))
		{
            return $_POST[$key];
        }
        return false;
    }

    /**
	 * Verifica se determinada key existe nas variaveis $_POST ou $_GET
	 * @param string $key nome da key
	 * @return false|mixed conteudo ou false (se não existir)
	 */
    public function contains($key)
	{
        return (isset($_GET[$key]) || isset($_POST[$key]));
    }

	/**
	 * Recupera informações de POST ou GET ($from)
	 * De acordo com os nomes das keys enviadas em forma de array
	 *
	 * Caso o valor não seja encontrado e a variavel $required receba 'true'
	 * retorna false.
	 * Se $require for 'false', retorna null no campos desejado
	 *
	 * Exemplo ($required falso):
	 * - Array: ['name', 'id']
	 * - Resposta: ['name' => 'test', 'id' => null]
	 * Exemplo ($required verdadeiro):
	 * - Array: ['name', 'id']
	 * - Resposta: false
	 *
	 * @param string $from 'POST' ou 'GET'
	 * @param array $keys ['name', 'id', 'session_id']
	 * @param boolean $required determina se é aceitavel não ter um dos valores requisitados
	 * @return \array|boolean
	 */
	public function unpack($from, array $keys, $required = true)
	{
		$params = [];
		$temp = ($from === 'POST') ? $_POST : $_GET;

		foreach($keys as $key)
		{
			if (isset($temp[$key]))
			{
				$params[$key] = $temp[$key];
			} else
			{
				if($required)
				{
					return false;
				}
				$params[$key] = null;
			}
		}
		return $params;
	}

}