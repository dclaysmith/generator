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

use dclaysmith\Generator\Template\DatabaseTemplate;
use dclaysmith\Generator\Formatter;
use dclaysmith\Generator\Connection;
use dclaysmith\Generator\Database\Table;

/**
 * Extendable base class for Table-based templates
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
abstract class TableTemplate extends DatabaseTemplate
{

	/**
	 * @var dclaysmith\Generator\Database\Table
	 */
	private $table;

	/**
	 * @param dclaysmith\Generator\Formatter
	 */
	function __construct(Formatter $formatter, Connection $connection, Table $table)
	{
		$this->table = $table;
		parent::__construct($formatter, $connection);
	}

	/**
	 * @return dclaysmith\Generator\Database\Table
	 */
	public function getTable() 
	{
		return $this->table;
	}
}
?>