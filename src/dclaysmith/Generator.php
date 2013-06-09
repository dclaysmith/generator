<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dclaysmith;

use dclaysmith\Generator\Connection\MySql;

/**
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
class Generator 
{

    /**
     * @var boolean
     */
    private $debug;
    
    /**
     * @var array
     */
    private $connections;

    /**
     * @var array
     */
    private $templates;

    /**
     * @var dclaysmith\Generator\Formatter
     */
    private $formatter;

    /**
     * @var string
     */
    private $templateDirectory;

    /**
     * @return dclaysmith\Generator\Formatter
     */
    private function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * @return array
     */
    private function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @return array
     */
    private function getConnections()
    {
        return $this->connections;
    }

    /**
     * @return dclaysmith\Generator\Connection
     */
    private function getConnection($identifier) 
    {
        return ($this->connections[$identifier]) ? $this->connections[$identifier] : false;
    }

    /**
     * @return string
     */
    private function getTemplateDirectory()
    {
        return $this->templateDirectory;
    }

    /**
     * @param string $config (json);
     */
    public function __construct($config) 
    {

        $config = json_decode($config);

        if (json_last_error() != "") die("Error decoding JSON. Error code: ".json_last_error());

        $this->validateConfig($config);

        // connections
        $this->connections = array();
        foreach ($config->connections as $connection) {
            $this->connections[$connection->name] = new MySql(
                                                        $connection->host,
                                                        $connection->user,
                                                        $connection->password,
                                                        $connection->database);             
        }

        // templates
        $this->templates = array();
        foreach ($config->templates as $template) {
            $this->templates[]      = $template;
        }

        // debug
        $this->debug                = $config->debug;

        // templateDirectory
        $this->templateDirectory    = $config->templateDirectory;

    }

    /**
     * @return void
     */
    public function run() 
    {

        foreach ($this->getTemplates() as $templateConfig)
        {
            $this->processTemplate($templateConfig);
        }

    }
    
    /**
     * @return void
     */
    private function processTemplate($templateConfig) 
    {

        $templateName       = $templateConfig->name;

        $templatePath       = $this->getTemplateDirectory().DIRECTORY_SEPARATOR.$templateName.".php";

        require_once($templatePath);

        $className          = "dclaysmith\\Generator\\Template\\".$templateName;        

        $template           = new $className;

        $template->tables   = $this->getConnection($templateConfig->connection)->tables();

        if ($templateConfig->repeat == "table") {

            foreach ($template->tables as $table) {

                if (!$output = $template->generate($table)) continue;
                
                $filename           = $template->filename($table->name);
                $destination        = $templateConfig->outputDirectory.DIRECTORY_SEPARATOR.$filename;

                if (file_exists($destination) && !$templateConfig->overwrite) continue;

                $handle             = (file_exists($destination)) ? fopen($destination, "w+") : fopen($destination, "x+");
                fwrite($handle, $output);
                fclose($handle);

                echo ". ".$filename."\n";
            }

        } else {

                if (!$output        = $template->generate()) continue;
                
                $filename           = $template->filename($table->name);
                $destination        = $templateConfig->outputDirectory.DIRECTORY_SEPARATOR.$filename;

                if (file_exists($destination) && !$templateConfig->overwrite) continue;
                
                $handle             = (file_exists($destination)) ? fopen($destination, "w+") : fopen($destination, "x+");
                fwrite($handle, $output);
                fclose($handle);

                echo ". ".$filename."\n";
        }

    }

    /**
     * @return void
     */
    private function validateConfig() 
    {

    }

}
?>