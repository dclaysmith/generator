<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// register the autoloader
spl_autoload_register( "DCSAutoloader" );

// autoloader
function DCSAutoloader( $class )
{

    // it will only autoload classes into the generator scope
    if( preg_match('/^dclaysmith/', $class ) )
    {

    	$class = str_replace("dclaysmith\\","",$class);

    	$class = str_replace("\\","_",$class);

		require_once 'phar://generator.phar/' . $class . ".php";
    }
    
}

use dclaysmith\Generator;

$configuration 	= file_get_contents("generator.json");

$generator 		= new Generator($configuration);

$generator->run();
?>