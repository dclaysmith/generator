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

use dclaysmith\Generator\Connection;
use dclaysmith\Generator\Database\Table;
use dclaysmith\Generator\Database\Column;

class MySql extends Connection implements IConnection 
{

    /**
     * @var string
     */
    private $host;
    
    /**
     * @var string
     */ 
    private $username;
    
    /**
     * @var string
     */    
    private $password;
    
    /**
     * @var string
     */
    private $database;

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @return void
     */
    public function __construct($host="", $username="", $password="", $database="") 
    {
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    /**
     * @return \PDO
     */
    public function getConnection() 
    {
        return new \PDO("mysql:host=".$this->host.";dbname=information_schema", $this->username, $this->password);
    }

    /**
     * @return array
     */
    public function getTables() 
    {
        $dbh    = $this->getConnection();

        $stmt   = $dbh->prepare("SELECT `TABLE_NAME` FROM `TABLES` WHERE `TABLE_SCHEMA` = ?");

        $tables = array();

        if ($stmt->execute(array($this->database))) 
        {
            while ($row = $stmt->fetch()) 
            {
                $tables[$row['TABLE_NAME']] = new Table($this, $row['TABLE_NAME']);
            }
        }

        return $tables;
    }

    public function getColumns($tableName)
    {
        $pattern = "/^([A-Za-z]+)(\({1}([0-9]+)\){1})?.*?$/";

        $dbh = new \PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->username, $this->password);

        $stmt = $dbh->prepare("DESCRIBE ?");

        $columns = array();

        if ($stmt->execute(array($tableName))) 
        {
            while ($row = $stmt->fetch()) 
            {
                $column                 = new Column($this);
                $column->name           = $row["Field"];
                $column->nullable       = ($row["Null"] == "YES") ? true : false;
                $column->primaryKey     = ($row["Key"] == "PRI") ? true : false;
                $column->default        = $row["Default"];
                $column->autoIncrement  = ($row['Extra'] == "auto_increment") ? true : false;

                if (preg_match($pattern, $row['Type'], $matches)) 
                {
                    if (count($matches) == 2) 
                    {
                        $column->type   = $matches[1];
                        $column->length = 0;
                    } 
                    else if (count($matches) == 4) 
                    {
                        $column->type   = $matches[1];
                        $column->length = $matches[3];
                    }

                    $matches = null;
                }

                $columns[] = $column;               
            }
        }

        return $columns;
    }

    public function getRows($tableName) 
    {
        $dbh = new \PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->username, $this->password);

        $rows = array();
        
        $stmt = $dbh->prepare("SELECT * FROM ?");   
        
        if ($stmt->execute(array($tableName))) 
        {
            while ($row = $stmt->fetch()) 
            {
                $rows[] = $row;
            }
        }   
        return $rows;
    }
}

?>