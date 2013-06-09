<?php

/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once("src/dclaysmith/Generator/Compiler.php");

use dclaysmith\Generator\Compiler;

$generator = new Compiler();

$generator->compile( realpath("./") . "/bin/generator.phar" );
	
?>