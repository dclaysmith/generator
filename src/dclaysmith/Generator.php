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
use dclaysmith\Generator\Formatter;

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
    private function getConnection($name) 
    {
        return ($this->connections[$name]) ? $this->connections[$name] : false;
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

        // debug
        $this->debug                = $config->debug;

        // templateDirectory
        $this->templateDirectory    = $config->templateDirectory;

        // connections
        $this->connections = array();
        foreach ($config->connections as $connection) 
        {
            if ($connection->type == "mysql") 
            {
                $this->connections[$connection->name] = new MySql(
                                                            $connection->host,
                                                            $connection->user,
                                                            $connection->password,
                                                            $connection->database);
            }         
        }

        // templates
        $this->templates = array();
        foreach ($config->templates as $template) 
        {
            $this->templates[] = $template;
        }

        // formatter
        $this->formatter = new Formatter((array) $config->pluralForms);
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
        // include the template file
        $templatePath           = $this->getTemplateDirectory()     .
                                            DIRECTORY_SEPARATOR     .
                                            $templateConfig->name   .
                                            ".php";     

        require_once($templatePath);

        // create an instance of the template
        $className              = "dclaysmith\\Generator\\Template\\".$templateConfig->name;   

        if ($templateConfig->repeat == "table") 
        {
            // retrieve the connections specified for this template
            $connection = $this->getConnection($templateConfig->connection);

            // loop through the tables for this connection
            foreach ($connection->getTables() as $table) 
            {

                // create an instance of the Template
                $template           = new $className(
                                            $this->getFormatter(),
                                            $connection,
                                            $table                                            
                                        );

                // if the output is blank, skip it
                if (!$output = $template->generate()) 
                {
                    continue;
                }

                $destination        = $templateConfig->outputDirectory.DIRECTORY_SEPARATOR.$template->formatFilename();

                $this->write($destination, $output, $templateConfig->overwrite);

                echo ". ".$template->formatFilename()."\n";
            }
        } 
        else 
        {
            // create an instance of the Template
            $template               = new $className(
                                        $this->getFormatter(),
                                        $connection                                     
                                    );         

            if (!$output = $template->generate())
            {
                continue;  
            } 
            
            $destination        = $templateConfig->outputDirectory.DIRECTORY_SEPARATOR.$template->formatFilename();
            
            $this->write($destination, $output, $templateConfig->overwrite);

            echo ". ".$template->formatFilename()."\n";
        }
    }

    /**
     * @return void
     */
    private function validateConfig() 
    {

    }

    /**
     * @param string $destination
     * @param string $output
     * @param boolean $overwrite
     */
    private function write($destination, $output, $overwrite = false)
    {
        if (file_exists($destination) && !$overwrite)
        {
            return;
        }

        $handle = (file_exists($destination)) ? fopen($destination, "w+") : fopen($destination, "x+");
        fwrite($handle, $output);
        fclose($handle);
    }
}
?>