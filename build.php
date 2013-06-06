<?php
require_once("src/dclaysmith/Compiler.php");

use dclaysmith\Compiler;

$generator = new Compiler();
$generator->compile( realpath("./") . "/bin/generator.phar" );
	
?>