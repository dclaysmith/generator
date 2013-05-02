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
	            		"file_name"		=> function ($table_name) { 
	            								
	            								switch (substr($table_name,0,6)) {
	            									case "tbl_p_":
	            										$prefix = "boP";
	            										break;
	            									case "tbl_c_":
	            										$prefix = "boC";
	            										break;
	            									default:
	            										$prefix	= "";
	            										break;
	            								}

	            								$sObjectName = str_replace(array('tbl_c_','tbl_p_'),'',$table_name);
	            								$sObjectName = str_replace(' ','',ucwords(str_replace('_',' ',$sObjectName)));
	            								$sObjectName = str_replace('-','_',$sObjectName);

	            								return $prefix.$sObjectName.".php";
	            							},
	            		"overwrite"		=> true
	            	),
	            	// array(
	            	// 	"template_name" => "ListTemplate",
	            	// 	"description"	=> "Generate a List View",
	            	// 	"repeat"		=> "table",
	            	// 	"destination"	=> "",
	            	// 	"file_name"		=> function ($table_name) { return "list_".$table_name.".php"; },
	            	// 	"overwrite"		=> false
	            	// ),
	            	// array(
	            	// 	"template_name" => "UpdateTemplate",
	            	// 	"description"	=> "Generate an update View",
	            	// 	"repeat"		=> "table",
	            	// 	"destination"	=> "",
	            	// 	"file_name"		=> function ($table_name) { return "update_".$table_name.".php"; },
	            	// 	"overwrite"		=> false
	            	// )
	            )
	       );

$generator 								= new Generator;
$generator->connection  				= $connection;
$generator->config 						= $config;
$generator->run();
?>