<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
Namespace dclaysmith\Generator;

class Database {

	protected $connection;
	protected $tables;

	public function __construct($connection) {

		$this->connection = $connection;
	}

	public function tables() {

		if (!$this->tables) {

			// retrieve a list of tables from the connection
			$this->tables = $this->connection->getTables();
		}

		return $this->tables;
	}
}

?>