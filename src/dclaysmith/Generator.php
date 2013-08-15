<?php
/**
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
 * class Generator
 *
 * Given a configuration file (generator.json) will process templates
 * and produce code. You can pass in 
 *
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
class Generator
{

    /**
     * @var boolean
     */
    private $_debug;

    /**
     * @var array
     */
    private $_connections;

    /**
     * @var array
     */
    private $_templates;

    /**
     * @var dclaysmith\Generator\Formatter
     */
    private $_formatter;

    /**
     * @var string
     */
    private $_templateDirectory;

    /**
     * @return dclaysmith\Generator\Formatter
     */
    private function getFormatter()
    {
        return $this->_formatter;
    }

    /**
     * @return array
     */
    private function getTemplates()
    {
        return $this->_templates;
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
        return ($this->_connections[$name]) ? 
                    $this->_connections[$name] : false;
    }

    /**
     * @return string
     */
    private function getTemplateDirectory()
    {
        return $this->_templateDirectory;
    }

    /**
     * @param string $config (json);
     */
    public function __construct($config) 
    {

        $config = json_decode($config);

        if (json_last_error() != "") {
            die("Error decoding JSON. Error code: ".json_last_error());
        }

        $this->validateConfig($config);

        // debug
        $this->_debug                = $config->debug;

        // templateDirectory
        $this->_templateDirectory    = $config->templateDirectory;

        // connections
        $this->_connections = array();
        foreach ($config->connections as $connection) {
            if ($connection->type == "mysql") {
                $this->_connections[$connection->name] = new MySql(
                    $connection->host,
                    $connection->user,
                    $connection->password,
                    $connection->database
                );
            }         
        }

        // templates
        $this->_templates = array();
        foreach ($config->templates as $template) {
            $this->_templates[] = $template;
        }

        // formatter
        $this->_formatter = new Formatter((array) $config->pluralForms);
    }

    /**
     * @return void
     */
    public function run() 
    {
        foreach ($this->getTemplates() as $templateConfig) {
            $this->processTemplate($templateConfig);
        }
    }
    
    /**
     * @return void
     */
    private function processTemplate($templateConfig) 
    {
        // include the template file
        $templatePath = $this->getTemplateDirectory() .
                            DIRECTORY_SEPARATOR .
                            $templateConfig->name .
                            ".php";     

        require_once($templatePath);

        // create an instance of the template
        $className = "dclaysmith\\Generator\\Template\\".$templateConfig->name; 

        if ($templateConfig->repeat == "table") {
            // retrieve the connections specified for this template
            $connection = $this->getConnection($templateConfig->connection);

            // loop through the tables for this connection
            foreach ($connection->getTables() as $table) {

                // create an instance of the Template
                $template = new $className(
                                            $this->getFormatter(),
                                            $connection,
                                            $table
                                        );

                // if the output is blank, skip it
                if (!$output = $template->generate()) {
                    continue;
                }

                $destination = $templateConfig->outputDirectory .
                                    DIRECTORY_SEPARATOR .
                                    $template->formatFilename();

                $this->write($destination, $output, $templateConfig->overwrite);

                echo ". ".$template->formatFilename()."\n";
            }
        } else {
            // create an instance of the Template
            $template               = new $className(
                                        $this->getFormatter(),
                                        $connection
                                    );         

            if (!$output = $template->generate()) {
                continue;  
            } 
            
            $destination = $templateConfig->outputDirectory .
                            DIRECTORY_SEPARATOR .
                            $template->formatFilename();
            
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
        if (file_exists($destination) && !$overwrite) {
            return;
        }

        $handle = (file_exists($destination)) ?
                    fopen($destination, "w+") : fopen($destination, "x+");

        fwrite($handle, $output);
        fclose($handle);
    }
}