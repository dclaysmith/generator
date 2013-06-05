<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
Namespace dclaysmith;

class Generator {

	public $connections = false;

	public function __construct($sConfigJson) {

		/**
		 * Read the config file
		 */
		$this->config = json_decode($sConfigJson);

		if (json_last_error() != "") die("Error decoding JSON. Error code: ".json_last_error());

		/**
		 * Validate the configuration file
		 * (consider Symfony\Component\Config)
		 */
		// $this->validateConfig();

	}

	public function run() {

		/**
		 * Loop through the templates
		 */
		foreach ($this->config->templates as $templateConfig) {
			$this->processTemplate($templateConfig);
		}

	}
	
	private function processTemplate($templateConfig) {

		$templateName		= $templateConfig->name;

		$templatePath 		= $this->config->templateDirectory.DIRECTORY_SEPARATOR.$templateName.".php";

		require_once($templatePath);

		$className 			= "dclaysmith\\Generator\\Template\\".$templateName;		

		$template 			= new $className;

		$template->tables 	= $this->getConnection($templateConfig->connection)->tables();

		if ($templateConfig->repeat == "table") {

			foreach ($template->tables as $table) {

				if (!$output 			= $template->generate($table)) continue;
				
				$filename			= $template->filename($table->name);
				$destination		= $this->config->outputDirectory.$templateConfig->outputDirectory.DIRECTORY_SEPARATOR.$filename;

				$handle 			= (file_exists($destination)) ? fopen($destination, "w+") : fopen($destination, "x+");
				fwrite($handle, $output);
				fclose($handle);

				echo ".";
			}

		} else {




			$template->tables  	= $this->database->tables();
			$output 			= $template->generate();		

			$filename			= $this->config["output_dir"].DIRECTORY_SEPARATOR.$template_data["output_dir"].DIRECTORY_SEPARATOR.$template_data["file_name"]();	

			$handle 			= (file_exists($filename)) ? fopen($filename, "w+") : fopen($filename, "x+");
			fwrite($handle, $output);
			fclose($handle);

			echo ".";
		}

	}

	private function validateConfig() {}

	private function getConnection($identifier) {
		if (!$this->connections) {
			$this->connections = array();
			foreach ($this->config->connections as $connection) {
				$this->connections[$connection->name] = new \dclaysmith\Generator\Connection\MySql(
																			$connection->host,
																			$connection->user,
																			$connection->password,
																			$connection->database); 
			}			
		}
		return ($this->connections[$identifier]) ? $this->connections[$identifier] : false;
	}

}


?>