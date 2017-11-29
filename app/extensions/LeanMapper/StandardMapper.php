<?php

namespace Model\Mapper;

use LeanMapper;

/**
 * Class Mapper
 * @package Model\Mapper
 */
class StandardMapper extends LeanMapper\DefaultMapper
{
	/**
	 * @param $str
	 *
	 * @return string
	 */
	public static function toUnderScore($str)
	{
		return lcfirst(preg_replace_callback('#(?<=.)([A-Z])#', function ($m) {
			return '_' . strtolower($m[1]);
		}, $str));
	}

	/**
	 * @param $str
	 *
	 * @return mixed
	 */
	public static function toCamelCase($str)
	{
		return preg_replace_callback('#_(.)#', function ($m) {
			return strtoupper($m[1]);
		}, $str);
	}

	/**
	 * @param string $entityClass
	 *
	 * @return string
	 */
	public function getTable($entityClass)
	{
		return self::toUnderScore($this->trimNamespace($entityClass));
	}

	/**
	 * @param string $table
	 * @param LeanMapper\Row|null $row
	 *
	 * @return string
	 */
	public function getEntityClass($table, LeanMapper\Row $row = NULL)
	{
		return ($this->defaultEntityNamespace !== NULL ? $this->defaultEntityNamespace . '\\' : '')
		       . ucfirst(self::toCamelCase($table)); // Název třídy začíná velkým písmenem
	}

	/**
	 * @param string $entityClass
	 * @param string $field
	 *
	 * @return string
	 */
	public function getColumn($entityClass, $field)
	{
		return self::toUnderScore($field);
	}

	/**
	 * @param string $table
	 * @param string $column
	 *
	 * @return mixed
	 */
	public function getEntityField($table, $column)
	{
		return self::toCamelCase($column);
	}

	/**
	 * @param string $repositoryClass
	 *
	 * @return string
	 * @throws LeanMapper\Exception\InvalidStateException
	 */
	public function getTableByRepositoryClass($repositoryClass)
	{
		$matches = array();
		if (preg_match('#([a-z0-9]+)repository$#i', $repositoryClass, $matches)) {
			return self::toUnderScore($matches[1]);
		}
		throw new LeanMapper\Exception\InvalidStateException('Cannot determine table name.');
	}
}