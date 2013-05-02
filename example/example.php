<?php

// include
require "../src/dclaysmith/bootstrap.php";

// namespace
use dclaysmith\Generator;

$connection 							= new dclaysmith\Generator\Connection\MySql;
$connection->host 						= "127.0.0.1";
$connection->username 					= "root";
$connection->password 					= "";
$connection->database 					= "crm_app";

// config
$config = array(
	            "cache_dir"     		=> "./cache",
	            "template_dir"  		=> "./templates",
	            "output_dir"			=> "./destination",
	            "debug"         		=> true,
	            "templates" 			=> array(
	            	array(
	            		"template_name" => "DataObjectTemplate",
	            		"description"	=> "Provide data access to each table",
	            		"repeat"		=> "table",
	            		"destination"	=> "do/",
	            		"file_name"		=> function ($database_name, $table_name) { return "dataobject".$table_name.".php"; },
	            		"overwrite"		=> true
	            	),
	            	array(
	            		"template_name" => "ListTemplate",
	            		"description"	=> "Generate a List View",
	            		"repeat"		=> "table",
	            		"destination"	=> "",
	            		"file_name"		=> function ($database_name, $table_name) { return "list_".$table_name.".php"; },
	            		"overwrite"		=> false
	            	),
	            	array(
	            		"template_name" => "UpdateTemplate",
	            		"description"	=> "Generate an update View",
	            		"repeat"		=> "table",
	            		"destination"	=> "",
	            		"file_name"		=> function ($database_name, $table_name) { return "update_".$table_name.".php"; },
	            		"overwrite"		=> false
	            	)
	            )
	       );

$generator 								= new Generator;
$generator->connection  				= $connection;
$generator->config 						= $config;
$generator->run();
?>