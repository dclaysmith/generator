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
	 * @return array
	 */
	public function getTables()
	{
		return $this->$tables;
	}

	/**
	 * @return dclaysmith\Generator\Connection
	 */
	public function getConnection() {
		return $this->connection;
	}
}

?>