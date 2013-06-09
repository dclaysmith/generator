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
	 * @return dclaysmith\Generator\Database\Table
	 */
	public function getTable() 
	{
		return $this->table;
	}
}
?>