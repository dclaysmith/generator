<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dclaysmith\Generator;

/**
 * Extendable base class for all templates
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
abstract class Template 
{

	/**
	 * @var dclaysmith\Generator\Formatter
	 */
	private $formatter;

	function filename($tableName) 
	{
		return $tableName;
	}

	/**
	 * @param string $value
	 * @return dclaysmith\Generator\Formatter
	 */
	protected function formatter($value) 
	{
		if (!$this->formatter) 
		{
			$this->formatter = new Formatter;
		}

		$this->formatter->initialize($value);

		return $this->formatter;			
	}
}

/**
 * Extendable base class for Database-Specific templates
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
abstract class DatabaseTemplate 
{

	/**
	 * @var array
	 */
	private $tables;

}

/**
 * Extendable base class for Table-Specific templates
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
abstract class TableTemplate 
{

	/**
	 * @var dclaysmith\Generator\Database\Table
	 */
	private $table;

	/**
	 * @return dclaysmith\Generator\Database\Table
	 */
	public function getTable() 
	{
		return $this->table;
	}
}
?>