<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
Namespace dclaysmith\Generator\Connection;

class MySql implements IConnection {

	public $host;
	public $username;
	public $password;
	public $database;

	public function __construct($host="", $username="", $password="", $database="") {
		$this->host 	= $host;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
	}

	public function getConnection() {

	}

	public function getTables() {

		$dbh = new \PDO("mysql:host=".$this->host.";dbname=information_schema", $this->username, $this->password);

		$stmt = $dbh->prepare("SELECT `TABLE_NAME` FROM `COLUMNS` WHERE `COLUMNS`.`TABLE_SCHEMA` = ?");

		$tables = array();

		if ($stmt->execute(array($this->database))) {
			while ($row = $stmt->fetch()) {
				$tables[] = new \dclaysmith\Generator\Database\Table($this, $row['TABLE_NAME']);
			}
		}

		return $tables;
	}

	public function getColumns($table_name) {

		$pattern = "/^([A-Za-z]+)(\({1}([0-9]+)\){1})?.*?$/";

		$dbh = new \PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->username, $this->password);

		$q = $dbh->prepare("DESCRIBE `tbl_p_account`");
		$q->execute();
		$table_fields = $q->fetchAll(\PDO::FETCH_COLUMN);
p($table_fields);
		foreach ($data as $row) {
			
			$column 				= new \dclaysmith\Generator\Database\Column($this);
			$column->name 			= $row["Field"];
			$column->nullable 		= ($row["Null"] == "YES") ? true : false;
			$column->primaryKey 	= ($row["Key"] == "PRI") ? true : false;
			$column->default 		= $row["Default"];
			$column->autoIncrement	= ($row['Extra'] == "auto_increment") ? true : false;

			if (preg_match($pattern,$type,$matches)) {
				if (count($matches) == 2) {
					$column->type 	= $matches[1];
					$column->length = 0;
				} else if (count($matches) == 4) {
					$column->type   = $matches[1];
					$column->length = $matches[3];
				}
				$matches = null;
			}
			$columns[] = $column;
		}

		return $columns;
	}

	public function getRows($table_name) {

	}
}

?>