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
 * Extendable base class for all templates
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
abstract class Template 
{

	/**
	 * @var dclaysmith\Generator\Formatter
	 */
	public $formatter;

	function filename($tableName) 
	{
		return $tableName;
	}

	/**
	 * @param string $value
	 * @return dclaysmith\Generator\Formatter
	 */
	protected function getFormatter($value) 
	{
		if (!$this->formatter)
		{
			throw new \exception("No formatter specified for the template.");
		}

		$this->formatter->initialize($value);
		return $this->formatter;			
	}
}

?>