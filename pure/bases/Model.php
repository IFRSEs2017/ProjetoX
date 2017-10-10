<?php
namespace Pure\Bases;
use Pure\Db\SQLBuilder;
use Pure\Db\Database;
use Pure\Db\SQLType;
use App\Configs\Config;

/**
 * Classe básica para Models
 *
 * Deve servir como base para outros models, é
 * dotado de capacidade de acesso e funções básicas da
 * tabela a qual representa
 *
 * @abstract
 * @version 1.0
 * @author Equipe Projeto X
 */
abstract class Model
{
	// Presente em todas as tabelas de modelo
	public $id;

	/**
	 * Método que recupera o mapeamento das colunas das tabelas
	 * Sendo a chave o nome da coluna presente na tabela
	 * e o tipo de dado o valor do array
	 * Caso o usuário tenha personalizado o método Class::table_map()
	 * retorna o mapeamento alternativo
	 *
	 * @internal
	 * @return array chave-valor ['nome_da_coluna' => 'tipo_de_dado']
	 */
	protected static function get_table_map()
	{
		$called = get_called_class();
		if(!method_exists($called, 'table_map'))
		{
			$sql = new SQLBuilder();
			// Faz consulta sobre a estrutura da tabela
			$response = $sql->builder(
				'SELECT column_name col, data_type dat FROM information_schema.columns WHERE table_name = \'' .
				self::get_table_name() .
				'\' AND table_schema = \'' .
				Config::database()['dbname'] .
				'\'')->execute();
			$map = [];
			// Cria mapeamento
			foreach($response as $object)
			{
				$map[$object->col] = $object->dat;
			}
			return $map;
		}
		// Caso o usuário tenha personalizado um mapeamento diferente
		return $called::table_map();
	}

	/**
	 * Método que recupera o nome da tabela no banco de dados
	 * Por padrão, Pure entende que o mesmo nome do modelo será utilizado no banco de dados
	 * Caso o usuário tenha personalizado o método Class::table_name()
	 * retorna o nome personalizado
	 *
	 * @internal
	 * @return string nome do banco de dados
	 */
	protected static function get_table_name()
	{
		$called = get_called_class();
		if(method_exists($called, 'table_name'))
		{
			// Caso o usuário tenha personalizado um nome diferente para a tabela em banco
			return $called::table_name();
		}
		// Nome baseado no nome da classe
		return strtolower(explode('\\',$called)[2]);
	}

	/**
	 * Realiza uma consulta rápida com valores pré-estabelecidos,
	 * por padrão se o método for chamada sem envio de parametro ela realizará a SQLBuilder:
	 * - SELECT * FROM table;
	 * Enviando um valor inteiro, realizará a consulta na chave primaria da tabela:
	 * - SELECT * FROM table WHERE id = :param;
	 * Enviando um array de informações (chave-valor), realizará filtros com WHERE como:
	 * - SELECT * FROM table WHERE :key1 = :value1 AND :key2 = :value2;
	 *
	 * Ob.: Caso haja somente uma corespondência para a consulta, como no caso do id,
	 * retorna o objeto sem o encapsulamento do array
	 *
	 * @param integer or array $filters filtros para a WHERE
	 * @return array|object|null resposta da consulta
	 */
	public static function find($filters = null)
	{
		$sql = new SQLBuilder();
		$sql->builder('SELECT * FROM ');
		if ($filters === null)
		{
			$sql->builder(self::get_table_name());
		}
		else if (is_array($filters))
		{
			$sql->builder(self::get_table_name() . ' WHERE ' .
				implode(' && ', array_map(
						function ($key, $value) {
							return $key . ' LIKE ' . var_export($value, true);
						},
						array_keys($filters),
						$filters
					)
				)
			);
		}
		else if (is_int(intval($filters)))
		{
			$sql->builder(self::get_table_name() . ' WHERE id = ' . $filters);
		}
		$response = $sql->execute();
		if (sizeof($response) === 1)
		{
			return $response[0];
		} else if (sizeof($response) > 0)
		{
			return $response;
		} else
		{
			return null;
		}
	}

	/**
	 * Realiza a inserção rápida de dados.
	 * Caso a instance do objeto passada por parâmetro possua um id,
	 * o objeto será atualizado em banco de dados utilizando UPDATE
	 * Caso não exista id no objeto, será realizada uma nova entrada por meio do INSERT
	 *
	 * @param Model $entity
	 * @return false or integer resposta do banco de dados
	 */
	public static function save($entity)
	{
		if (isset($entity->id) && intval($entity->id))
		{
			return self::quick_update($entity);
		}
		else
		{
			return self::quick_insert($entity);
		}
	}

	/**
	 * Realiza a atualização de um objeto em banco de acordo com as informações
	 * digitadas pelo usuário utilizando o id como base de comparação
	 *
	 * @internal
	 * @param Model $entity objeto a ser atualizado
	 * @return false or integer resposta do banco de dados
	 */
	private static function quick_update($entity)
	{
		$map = self::get_table_map();
		$sql = new SQLBuilder(SQLType::DML);
		foreach($map as $column => &$value)
		{
			if(property_exists($entity, $column) && $entity->$column !== null)
			{
				$value = $entity->$column;
			}
			else
			{
				unset($map[$column]);
			}
		}
		return $sql->builder('UPDATE ' . self::get_table_name() . ' SET ' .
			self::iterator($map) .
			' WHERE id = ' .
			$map['id']
		)->execute();
	}

	/**
	 * Realiza a inserção de um objeto em banco de acordo com as informações
	 * contidas no objeto relacional
	 *
	 * @param Model $entity objeto a ser inserido
	 * @return false or integer resposta do banco de dados
	 */
	private static function quick_insert($entity)
	{
		$map = self::get_table_map();
		$sql = new SQLBuilder(SQLType::DML);
		foreach($map as $column => &$value)
		{
			if (property_exists($entity, $column) && $entity->$column !== null)
			{
				$value = var_export($entity->$column, true);
			}
			else
			{
				unset($map[$column]);
			}
		}
		return $sql->builder('INSERT INTO ' . self::get_table_name() . ' (' .
				implode(', ', array_keys($map)) .
				') VALUES (' .
				implode(', ', array_values($map)) .
				')'
		)->execute();
	}

	/**
	 * Realize a construção de um SQLBuilder para atualização de Dados
	 * retornando um objeto que utiliza o comando INSERT INTO em SQL
	 *
	 * O método recebe um array no formato chave-valor sendo a chave do
	 * array o nome da coluna e o valor o dado a ser inserido em banco
	 *
	 * - Array: ['chave_1' => 'valor_1']
	 * - Resultado: INSERT INTO table_name (chave_1) VALUES ('valor_1')
	 *
	 * - Array: ['chave_1' => 'valor_1', 'chave_2' => 'valor_2']
	 * - Resultado: INSERT INTO table_name (chave_1, chave_2)  VALUES ('valor_1', 'valor_2')
	 *
	 * @param array $columns dados em formato 'coluna' => 'valor'
	 * @return SQLBuilder objeto do SQLBuilder
	 */
	public static function insert(array $columns)
	{
		$sql = new SQLBuilder(SQLType::DML);
		$sql->builder('INSERT INTO ' . self::get_table_name());
		if (!empty($columns))
		{
			$sql->builder(
				'(' .
				implode(', ', array_keys($columns)) .
				') VALUES (' .
				implode(', ', array_values($columns)) .
				')'
			);
		}
		return $sql;
	}

	/**
	 * Realiza a construção de um SQLBuilder para atualização de Dados
	 * retornando um objeto que utiliza o comando UPDATE ... SET em SQL
	 *
	 * O método recebe um array no formato chave-valor sendo a chave
	 * do array o nome da coluna e o valor o dado a ser atualizado em banco
	 *
	 * É EXTREMAMENTE IMPORTANTE lembrar da utilização da cláusula
	 * WHERE na manipulação de dados com UPDATE ... SET.
	 *
	 * - Array: ['chave_1' => 'valor_1']
	 * - Resultado: UPDATE table_name SET chave_1 = 'valor_1'
	 *
	 * - Array: ['chave_1' => 'valor_1', 'chave_2' => 'valor_2']
	 * - Resultado: UPDATE table_name SET chave_1 = 'valor_1', chave_2 = 'valor_2'
	 *
	 * É possivel utilizar o parametro 'id' para especificar qual item será
	 * atualizado em banco
	 *
	 * @param array $columns dados em formato 'coluna' => 'valor'
	 * @param mixed $id especificação de id
	 * @return SQLBuilder objeto do SQLBuilder
	 */
	public static function update(array $columns = [], $id = null)
	{
		$sql = new SQLBuilder(SQLType::DML);
		$sql->builder('UPDATE ' . self::get_table_name() . ' SET ');
		if (!empty($columns))
		{
			$sql->builder(
				self::iterator($columns)
			);
			if ($id !== null && (int)$id)
			{
				$sql->builder(' WHERE id = ' . $id);
			}
		}
		return $sql;
	}

	/**
	 * Realiza a construção de um SQLBuilder para seleção de dados
	 * retornando um objeto que utiliza o comando SELECT em SQL.
	 *
	 * - String: 'name'
	 * - Resultado: SELECT name
	 *
	 * - Array: ['name', 'age']
	 * - Resultado: SELECT name, age FROM model
	 *
	 * @see Pure\Db\SQLBuilder
	 * @param string or array $columns filtro sobre colunas especificas da tabela
	 * @return SQLBuilder objeto do SQLBuilder
	 */
	public static function select($columns = [])
	{
		$sql = new SQLBuilder();
		$sql->builder('SELECT ');
		if (is_string($columns))
		{
			$sql->builder($columns);
		}
		else if (is_array($columns) && !empty($columns))
		{
			$sql->builder(implode(', ', $columns));
		}
		else
		{
			$sql->builder('*');
		}
		$sql->builder(' FROM ' . self::get_table_name());
		return $sql;
	}

	/**
	 * Realzia a construção de um SQLBuilder para exclusão de dados
	 * retornando um objeto que utiliza o comando DELETE FROM em SQL.
	 *
	 * É EXTREMAMENTE IMPORTANTE lembrar da utilização da cláusula
	 * WHERE na manipulação de dados com DELETE FROM.
	 *
	 * @return SQLBuilder objeto do SQLBuilder
	 */
	public static function delete()
	{
		$sql = new SQLBuilder(SQLType::DML);
		$sql->builder('DELETE FROM ' . self::get_table_name() . ' ');
		return $sql;
	}

	/**
	 * Método de criação personalizada
	 *
	 * Utiliza-se nos casos onde o SQLBuilder não é capaz de gerar
	 * consultas, pode-se inserir uma String SQL diretamente no sistema.
	 *
	 * @param string $sql String SQL completa
	 * @return SQLBuilder objeto do SQLBuilder
	 */
	public static function build($string = '', $type = SQLType::DQL)
	{
		$sql = new SQLBuilder($type);
		return $sql->builder($string);
	}

	/**
	 * Realiza o mapeamento de uma classe em uma string utilizada
	 * no SQLBuilder
	 *
	 * @param array $array dados a serem iterados
	 * @return string concatenação dos itens
	 */
	private static function iterator(array $array)
	{
		return implode(', ',
			array_map(
				function ($key, $value) {
					return $key . ' = ' . var_export($value, true);
				},
				array_keys($array),
				$array
			)
		);
	}

}