<?php
/*
 * This file is part of the dclaysmith namespace.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// register the autoloader
spl_autoload_register( "DCSAutoloader" );

// autoloader
function DCSAutoloader( $class ){

    // it will only autoload classes into the generator scope
    if( preg_match('/^dclaysmith/', $class ) )
    {

        // transform the namespace in path
        $path = str_replace("\\", DIRECTORY_SEPARATOR, $class );
        // filepath
        $path = __DIR__ . DIRECTORY_SEPARATOR . str_replace("dclaysmith/","",$path) . ".php";

        // require the file
        require_once $path;
    }
    
}
?>