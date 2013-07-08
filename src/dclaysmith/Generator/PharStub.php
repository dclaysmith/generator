<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use dclaysmith\Generator;

$configuration 	= file_get_contents("generator.json");

$formatter 		= new Formatter();

$generator 		= new Generator($configuration);

$generator->run();
?>