<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
if(!defined("BASE_DIR"))
    define("BASE_DIR", dirname( dirname(__DIR__) ) );

// register the autoloader
spl_autoload_register( "GeneratorAutoloader" );

// autoloader
function GeneratorAutoloader( $class ){

    // it will only autoload classes into the generator scope
    if( preg_match('#dclaysmith\\\Generator#', $class ) ){

        // transform the namespace in path
        $path = str_replace("\\", DIRECTORY_SEPARATOR, $class );

        // filepath
        $path = __DIR__ . DIRECTORY_SEPARATOR . str_replace("dclaysmith/","",$path) . ".php";

        // require the file
        require_once $path;
    }
    
}

function p($value) {
    echo "<pre>";
    echo print_r($value,true);
    die;
}
?>