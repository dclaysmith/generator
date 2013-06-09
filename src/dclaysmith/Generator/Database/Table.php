<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
Namespace dclaysmith\Generator\Database;

class Table 
{

	public $connection;
	public $name;
	public $columns;
	public $rows;

	public function __construct($connection, $name, $columns = null) 
	{

		$this->connection 	= $connection;
		$this->name 		= $name;
	
	}

	public function getColumns() 
	{

		if (!$this->columns) $this->columns = $this->connection->columns($this->name);

		return $this->columns;

	}

	public function getRows() 
	{

		if (!$this->rows) $this->rows = $this->connection->rows($this->name);

		return $this->rows;

	}	

}

?>