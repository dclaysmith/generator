<?php
require_once("src/dclaysmith/Generator/Compiler.php");

use dclaysmith\Generator\Compiler;

$compiler = new Compiler();

$compiler->compile( realpath("./") . "/bin/generator.phar" );
	
?>