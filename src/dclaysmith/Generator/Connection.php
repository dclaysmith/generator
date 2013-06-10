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
 * Abstract class defining a datasource connection
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
abstract class Connection 
{

	/**
	 * @var array
	 */
	private $tables;

	public function __construct($connection) 
	{
		$this->connection = $connection;
	}
	
}

?>