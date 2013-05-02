<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
function p($mixed) {
    echo "<pre>";
    echo print_r($mixed,true);
    die;
}

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
        $abs_path = BASE_DIR . "/src/" . $path . ".php";

        // require the file
        require_once $abs_path;
    }
    
}