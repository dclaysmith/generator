<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once("bootstrap.php");

use dclaysmith\Generator;

$sConfiguration = file_get_contents("generator.json");

$generator = new Generator($sConfiguration);

$generator->run();
?>