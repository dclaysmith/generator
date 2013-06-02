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

$generator = new Generator();
$generator->run();

class Generator {

	public static function run($sConfigJson) {

		$templates 	= $this->config["templates"];

		$this->database = new Generator\Database($this->connection);

		array_walk($templates, array($this,'processTemplate'));

	}
	
	private function processTemplate($template_data, $key) {
		
		$templateName	= $template_data["template_name"];

		$sPath 			= $this->config["template_dir"].DIRECTORY_SEPARATOR.$templateName.".php";

		require_once($sPath);

		$template 		= new $templateName;		

		if ($template_data["repeat"] == "table") {

			foreach ($this->database->tables() as $table) {

				$template->table 	= $table;	
				$template->tables  	= $this->database->tables();
				$output 			= $template->generate();		
				$filename			= $this->config["output_dir"].DIRECTORY_SEPARATOR.$template_data["output_dir"].DIRECTORY_SEPARATOR.$template_data["file_name"]($table->name);	

				$handle 			= (file_exists($filename)) ? fopen($filename, "w+") : fopen($filename, "x+");
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

}

?>