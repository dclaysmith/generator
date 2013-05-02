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

	public function run() {

		$templates 	= $this->config["templates"];

		$this->database = new Generator\Database($this->connection);

		array_walk($templates, array($this,'process_template'), $this->config);

	}
	
	private function process_template($template_data, $key, $config) {
		
		$class_name	= $template_data["template_name"];

		$sPath 		= $config["template_dir"].DIRECTORY_SEPARATOR.$class_name.".php";

		require_once($sPath);

		$template 	= new $class_name;		

		if ($template_data["repeat"] == "table") {

			foreach ($this->database->tables() as $table) {

				$template->table 	= $table;	
				$template->tables  	= $this->database->tables();
				$output 			= $template->generate();		
				$filename			= $config["output_dir"].DIRECTORY_SEPARATOR.$template_data["file_name"]($table->name);	
				
				$handle 			= (file_exists($filename)) ? fopen($filename, "w+") : fopen($filename, "x+");
				fwrite($handle, $output);
				fclose($handle);
			}

		} else {

			$output 			= $template->generate();		
			$filename			= $template_data["file_name"]("donkey");	
			echo $filename."\n";
		}


	}

}

?>