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
	            "cache_dir"     			=> "./cache",
	            "template_dir"  			=> "./templates",
	            "output_dir"				=> "./destination",
	            "debug"         			=> true,
	            "templates" 				=> array(
	            	array(
	            		"template_name" 	=> "DataObjectTemplate",
	            		"description"		=> "Provide data access to each table",
	            		"repeat"			=> "table",
	            		"output_dir"		=> "do",
	            		"file_name"			=> function ($table_name) { 
	            								
	            								switch (substr($table_name,0,6)) {
	            									case "tbl_p_":
	            										$prefix = "doP";
	            										break;
	            									case "tbl_c_":
	            										$prefix = "doC";
	            										break;
	            									default:
	            										$prefix	= "";
	            										break;
	            								}

	            								$sObjectName = str_replace(array('tbl_c_','tbl_p_'),'',$table_name);
	            								$sObjectName = ucwords(str_replace(array("-","_"),array("- ","_ "),$sObjectName));
	            								$sObjectName = ucwords(str_replace(array("- ","_ "),array("-","_"),$sObjectName));
	            								$sObjectName = str_replace(' ','',ucwords(str_replace('_',' ',$sObjectName)));
	            								$sObjectName = str_replace('-','_',$sObjectName);

	            								return $prefix.$sObjectName.".php";

	            							},
	            		"overwrite"			=> true
	            	)
	            )
	       );

$generator 								= new Generator;
$generator->connection  				= $connection;
$generator->config 						= $config;
$generator->run();
?>