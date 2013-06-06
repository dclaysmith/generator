<?php
require_once("bootstrap.php");

// there should be an option for specifying an alternate json file
$sConfiguration = file_get_contents("generator.json");

$generator = new \dclaysmith\Generator($sConfiguration);

$generator->run();
?>