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

class Table {

	public $connection;
	public $name;
	public $columns;

	public function __construct($connection, $name, $columns = null) {
		$this->connection 	= $connection;
		$this->name 		= $name;
	}

	public function columns() {

		if (!$this->columns) {
			
			// retrieve a list of columns from the connection
			$this->columns = $this->connection->getColumns($this->name);
		}

		return $this->columns;
	}
}

?>