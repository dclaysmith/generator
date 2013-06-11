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
 * Metadata for the database defined in a connection
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
class Database 
{

    /**
     * @var dclaysmith\Generator\Connection
     */
    private $connection;

    /**
     * @var array
     */
    private $tables;

    /**
     * @param dclaysmith\Generator\Connection
     */
    public function __construct(Connection $connection) 
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getTables() 
    {
        if (!$this->tables) 
        {
            $this->tables = $this->connection->getTables();
        }
        
        return $this->tables;
    }

}

?>