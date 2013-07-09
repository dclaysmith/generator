<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Create an instance of the Generator class and run it. Should be executed in
 * a directory containing a valid generator.json file.
 *
 * Example:
 *
 * > php /path/to/build.php
 */

require_once("src/dclaysmith/Generator/Compiler.php");

use dclaysmith\Generator\Compiler;

$compiler = new Compiler();

$compiler->compile( realpath("./") . "/bin/generator.phar" );
	
?>