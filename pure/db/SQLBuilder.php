<?php
namespace Pure\Db;
use Pure\Db\Database;
use Pure\Db\SQLType;

/**
 * Classe SQLBuilder
 *
 * Objeto representativo de uma String SQL,
 * a partir dela é possivel realizar consultas usando o método
 * 'execute' ou retornar uma strings usando o método 'generate'.
 * É possivel gerar consultas SQL completas, com filtros ou
 * com SQL Puro.
 *
 * @version 1.0
 * @author Equipe Projeto X
 */
class SQLBuilder
{
	private $string;
	private $type;

	/**
	 * Construtor da classe
	 *
	 * @param int $type tipo de linguagem SQL a ser utilizada
	 */
	public function __construct($type = SQLType::DQL)
	{
		$this->type = $type;
	}

	/**
	 * Função de criação personalizada
	 *
	 * Utiliza-se nos casos onde o SQLBuilder não é capaz de gerar
	 * a consulta desejada, pode-se inserir uma String SQL diretamente no sistema.
	 *
	 * @param string $sql String SQL
	 * @return SQLBuilder retorna a si mesmo, possibilitando Method Chaining.
	 * @link https://en.wikipedia.org/wiki/Method_chaining
	 */
	public function builder($sql)
	{
		$this->string .= $sql;
		return $this;
	}

	/**
	 * Realiza a filtragem de valores com a clausula WHERE
	 *
	 * O método recebe um array no formato chave-valor sendo a
	 * chave do array o nome da coluna e o valor o filtro escolhido
	 *
	 * - Array: ['chave_1' => 'valor_1']
	 * - Resultado: WHERE chave_1 = 'valor_1
	 *
	 * - Array: ['chave_1' => 'valor_1', 'chave_2' => 'valor_2']
	 * - Resultado: WHERE chave_1 = 'valor_1' AND chave_2 = 'valor_2'
	 *
	 * @param array $filters filtros em formato 'coluna' => 'valor'
	 * @return SQLBuilder retorna a si mesmo, possibilitando Method Chaining.
	 * @link https://en.wikipedia.org/wiki/Method_chaining
	 */
	public function where(array $filters)
	{
		if (!empty($filters))
		{
			$this->string .= $this->iterator($filters, 'WHERE');
		}
		return $this;
	}

	/**
	 * Realiza a filtragrem de valores com comparação
	 * entre a coluna e o valor de filtro por meio de um array chave-valor
	 *
	 * Adiciona WHERE (...) LIKE na consulta SQL, possibilitando a utilização de
	 * caracteres coringa '%'.
	 *
	 * - Array: ['chave_1' => '%alor_1', 'chave_2' => 'val%']
	 * - Resultado: WHERE chave_1 LIKE '%alor_1' AND chave_2 LIKE 'val%'
	 *
	 * @param array $filters filtros em formato 'coluna' => 'valor'
	 * @return SQLBuilder retorna a si mesmo, possibilitando Method Chaining.
	 * @link https://en.wikipedia.org/wiki/Method_chaining
	 */
	public function where_like(array $filters = [])
	{
		if (!empty($filters))
		{
			$this->string .= $this->iterator($filters, 'WHERE', 'AND', 'LIKE');
		}
		return $this;
	}

	/**
	 * Realiza o agrupamentos dos dados da consulta,
	 * agrupando de acordo com o valor escolhido.
	 *
	 * Adiciona GROUP BY na consulta SQL para cada coluna
	 *
	 * - String: 'coluna'
	 * - Resultado: GROUP BY coluna
	 *
	 * - Array: ['valor_1', 'valor_2', valor_3]
	 * - Resultado: GROUP BY valor_1, valor_2, valor_3
	 *
	 * @param string or array $columns coluna ou array de colunas que definem o agrupamento
	 * @return SQLBuilder objeto do Query Builder
	 */
	public function group_by($columns)
	{
		if(is_string($columns))
		{
			$this->string .= ' GROUP BY ' . $columns;
		}
		else if (is_array($columns) && !empty($columns))
		{
			$this->string .= ' GROUP BY ' . implode(', ', $columns);
		}
		return $this;
	}

	/**
	 * Realiza a seleção de valores dentro de um universo de posibilidaes
	 * representadas em um array de valores
	 *
	 * - Array: ['valor_1','valor_2','valor_3']
	 * - Resultado: IN ('valor_1', 'valor_2', valor_3)
	 *
	 * @param array $array
	 */
	public function in(array $array)
	{
		if(!empty($array))
		{
			$this->string .= ' IN (' . implode(', ', $array) . ') ';
		}
	}

	/**
	 * Realiza a ordenação dos dados presentes na consulta,
	 * ordenando de acordo com a chave (coluna) e o valor (forma de ordenação)
	 *
	 * Adiciona ORDER BY na consulta SQL para cada coluna
	 *
	 * - Array: ['valor_1' => 'ASC', 'valor_2' => 'DESC', 'valor_3' => 'ASC']
	 * - Resultado: ORDER BY valor_1 ASC, valor_2 DESC, valor_3 ASC
	 *
	 * @param array $filters filtros em formato 'coluna' => 'valor'
	 * @return SQLBuilder retorna a si mesmo, possibilitando Method Chaining.
	 * @link https://en.wikipedia.org/wiki/Method_chaining
	 */
	public function order_by(array $filters = [])
	{
		if (!empty($filters))
		{
			$this->string .= ' ORDER BY ' . implode(', ', array_map(
				function ($v, $k) {
					return $k . ' ' . $v;
				},
				$filters,
				array_keys($filters)
			));
		}
		return $this;
	}

	/**
	 * Adiciona mais uma tabela a consulta atual por meio de JOIN
	 *
	 * Utiliza-se o método "on()" para definir
	 * variaveis que serão comparadas na junção.
	 *
	 * - String: 'pessoa p'
	 * - Resultado: JOIN 'pessoa p'
	 *
	 * @see public function on()
	 * @param string $table_name nome da tabela que será adicionada a consulta
	 * @param string $type tipo de JOIN (LEFT, RIGHT, OUTER, FULL)
	 * @return SQLBuilder retorna a si mesmo, possibilitando Method Chaining.
	 * @link https://en.wikipedia.org/wiki/Method_chaining
	 */
	public function join($table_name, $type = '')
	{
		if(is_string($table_name))
		{
			$this->string .= ' ' . $type . ' JOIN ' . $table_name;
		}
		return $this;
	}

	/**
	 * Realiza a inserção da clausula HAVINGS, que faz a comparação entre
	 * valores e funções de agregação
	 *
	 * - String: 'nome'
	 * - Resultado: HAVING nome
	 *
	 * @param string $aggregation
	 */
	public function having($aggregation)
	{
		if(is_string($aggregation))
		{
			$this->string .= ' HAVING ' . $aggregation;
		}
	}

	/**
	 * Faz a relação entre as colunas que serão comparadas no JOIN
	 * Utiliza um array chave-valor para definir as tabelas que serão
	 * comparadas, separando essas junções com AND.
	 *
	 * - String: 'a.coluna'
	 * - Resultado: ON a.coluna
	 *
	 * - Array: ['a.coluna_1' => 'b.coluna_2']
	 * - Resultado: ON a.coluna_1 = b.coluna_2
	 *
	 * - Array: ['a.coluna_1' => 'b.coluna_2', 'a.coluna_3' => 'b.coluna_4']
	 * - Resultado: ON a.coluna_1 = b.coluna_2 AND a.coluna_3 = b.coluna_4
	 *
	 * @param string or array $filters nome da coluna ou array de colunas chave-valor
	 * @return SQLBuilder retorna a si mesmo, possibilitando Method Chaining.
	 * @link https://en.wikipedia.org/wiki/Method_chaining
	 */
	public function on($filters)
	{
		if (is_string($filters))
		{
			$this->string .= ' ON ' . $filters;
		}
		else if (is_array($filters) && !empty($filters))
		{
			$this->string .= $this->iterator($filters, 'ON');
		}
		return $this;
	}

	/**
	 * Cria um alias para o item anterior da consulta SQL
	 *
	 * Adiciona AS na consulta SQL para cada o item anterior
	 *
	 * - String: 'p'
	 * - Resultado: AS p
	 *
	 * @deprecated
	 * @param string $alias_name alias para a ultima tabela construída
	 * @return SQLBuilder objeto do Query Builder
	 */
	public function alias($alias_name)
	{
		if(is_string($alias_name))
		{
			$this->string .= ' AS ' . $alias_name;
		}
		return $this;
	}

	/**
	 * Limita o número de resultados buscado pela consulta
	 *
	 * Adiciona LIMIT na consulta SQL
	 *
	 * - Inteiro: 23
	 * - Resultado: LIMIT 23
	 *
	 * @param int $limit limite de resultados obtidos
	 * @return SQLBuilder objeto do Query Builder
	 */
	public function limit($limit)
	{
		if(is_int($limit))
		{
			$this->string .= ' LIMIT ' . $limit;
		}
		return $this;
	}

	/**
	 * Junto com o filtro de limite, permite a exibição de valores
	 * posteriores aos exibidos.
	 *
	 * Adiciona OFFSET na consulta SQL
	 *
	 * - Inteiro: 23
	 * - Resultado: LIMIT 23
	 *
	 * @param int $offset
	 * @return SQLBuilder objeto do Query Builder
	 */
	public function offset($offset)
	{
		if(is_int($offset))
		{
			$this->string .= ' OFFSET ' . $offset;
		}
		return $this;
	}

	/**
	 * Executa a consulta SQL gerada pelo SQLBuilder
	 * retornando os valores obtidos do banco de dados.
	 *
	 * @return array or boolean lista de objetos do banco de dados ou resultado da inserção
	 */
	public function execute()
	{
		$db = Database::get_instance();
		$result = false;
		switch($this->type)
		{
			case SQLType::DML:
			case SQLType::DDL:
			case SQLType::DCL:
				$result = $db->execute_update($this);
				break;
			case SQLType::DTL:
				$result = $db->execute_query($this);
				break;
			case SQLType::DQL:
			default:
				$result = $db->execute_query($this);
				break;
		}
		return $result;
	}

	/**
	 * Retorna a String de consulta gerada pelo SQLBuilder
	 *
	 * @return string String SQL
	 */
	public function generate()
	{
		return $this->string . ';';
	}

	/**
	 * Retorna a String iteradas com ovalores no formato SQL
	 *
	 * - Array: ['coluna_1' => 'valor_1', 'coluna_2' => 'valor_2']
	 * - String: WHERE
	 * - String: OR
	 * - String: LIKE
	 * - Resultado: WHERE coluna_1 LIKE 'valor_1' OR coluna_2 LIKE 'valor_2'
	 *
	 * @internal
	 * @param array $array chave-valor ['coluna' => 'valor']
	 * @param string $command comando SQL ('WHERE', 'ON')
	 * @param string $logical_operator operadores lógicos ('AND', 'OR')
	 * @param string $relational_operator operadores relacionais ('>', '<', '!=', 'LIKE' (...))
	 * @return string resultado da iteração do SQL
	 */
	private function iterator($array, $command, $logical_operator = 'AND', $relational_operator = '=')
	{
		return ' ' . $command . ' ' .
			implode(' ' . $logical_operator . ' ',
				array_map(
					function ($key, $value) use ($relational_operator) {
						return $key . ' ' . $relational_operator . ' ' . var_export($value, true);
					},
					array_keys($array),
					$array
				)
			);
	}
}