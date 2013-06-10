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

class Database 
{

    /**
     * @var dclaysmith\Generator\Connection
     */
    private $connection;

    /**
     * @var dclaysmith\Generator\array
     */
    private $tables;

    public function __construct($connection) 
    {

        $this->connection = $connection;

    }

    public function tables() 
    {

        if (!$this->tables) 
        {

            $this->tables = $this->connection->getTables();

        }
        
        return $this->tables;

    }

}

?>