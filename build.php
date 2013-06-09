<?php
require_once("src/dclaysmith/Generator/Compiler.php");

use dclaysmith\Generator\Compiler;

$generator = new Compiler();

$generator->compile( realpath("./") . "/bin/generator.phar" );
	
?>