<?php
namespace Pure\Db;
use Pure\Bases\Enum;

/**
 * Define os tipos básicos de consultas e inserções
 * 
 * @see Pure\Base\Enum;
 * @version 1.0
 * @author Equipe Projeto X
 */
abstract class SQLType extends Enum
{
	/**
	 * Linguagem de Manipulação de Dados
	 * Constante que define SQL Type para DML
	 * Data Manipulation Language
	 * INSERT, UPDATE e DELETE
	 */
	const DML = 1;
	/**
	 * Linguagem de Definição de Dados
	 * Constante que define SQL Type para DDL
	 * Data Definition Language
	 * CREATE, DROP, ALTER
	 */
	const DDL = 2;
	/**
	 * Linguagem de Controle de Dados
	 * Constante que define SQL Type para DCL
	 * Data Control Language
	 * GRANT, REVOKE
	 */
	const DCL = 3;
	/**
	 * Linguagem de Transação de Dados
	 * Constante que define SQL Type para DTL
	 * Data Transaction Language
	 * BEGIN WORK, COMMIT, ROLLBACK
	 */
	const DTL = 4;
	/**
	 * Linguagem de Consulta de Dados
	 * Constante que define SQL Type para DQL
	 * Data Query Language
	 * SELECT
	 */
	const DQL = 5;
}