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

class Template {

	public $table;
	public $tables;

	function filename($tableName) {
		return $tableName;
	}

	protected function formatter($value) {
        static $instance;
        if (!isset($instance)) {
            $instance = new Formatter;
        }
        $instance->initialize($value);
        return $instance;		
	}
}
?>