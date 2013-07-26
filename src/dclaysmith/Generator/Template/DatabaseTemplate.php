<?php

/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dclaysmith\Generator\Template;

use dclaysmith\Generator\Template;
use dclaysmith\Generator\Formatter;
use dclaysmith\Generator\Connection;

/**
 * Extendable base class for Database-based templates
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
abstract class DatabaseTemplate extends Template
{

	/**
	 * @var array
	 */
	private $tables;

	/**
	 * @var dclaysmith\Generator\Connection
	 */
	private $connection;

	/**
	 * @param dclaysmith\Generator\Formatter
	 */
	function __construct(Formatter $formatter, Connection $connection)
	{
		$this->connection = $connection;
		parent::__construct($formatter);
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

		if (!$this->tables)
		{
			throw new \exception("Unable to retrieve any tables.");
		}

		return $this->tables;
	}

	/**
	 * @return dclaysmith\Generator\Connection
	 */
	public function getConnection() 
	{
		return $this->connection;
	}
}

?>