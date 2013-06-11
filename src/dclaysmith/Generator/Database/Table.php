<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dclaysmith\Generator\Database;

/**
 * Representation of a single table in a database
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
class Table 
{

	/**
	 * @var dclaysmith\Generator\Connection
	 */
	public $connection;

	/**
	 * @var string
	 */	
	public $name;

	/**
	 * @var array
	 */	
	public $columns;

	/**
	 * @var array
	 */	
	public $rows;

	/**
	 * @param dclaysmith\Generator\Connection $connection
	 * @param string $name
	 * @return void
	 */
	public function __construct($connection, $name) 
	{
		$this->connection 	= $connection;
		$this->name 		= $name;
	}

	/**
	 * @return array
	 */
	public function getColumns() 
	{
		if (!$this->columns)
		{
			$this->columns = $this->connection->getColumns($this->name);
		}

		return $this->columns;
	}

	/**
	 * @return array
	 */
	public function getRows() 
	{
		if (!$this->rows)
		{
			$this->rows = $this->connection->getRows($this->name);
		}

		return $this->rows;
	}	

}

?>