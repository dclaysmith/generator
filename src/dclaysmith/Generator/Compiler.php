<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dclaysmith\Generator;

/**
 * The Compiler class compiles generator into a phar
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
class Compiler
{

    /**
     * Compiles composer into a single phar file
     * @param  string  $pharFile The full path to the file to create
     */
    public function compile($pharFile = 'generator.phar')
    {

        if (file_exists($pharFile)) 
        {
            unlink($pharFile);
        }

        $archiveContents = array();

        $archiveContents["Generator.php"]                           = "../Generator.php"; // contained one level up ?
        $archiveContents["Generator_Connection_MySql.php"]          = "Connection/MySql.php";
        $archiveContents["Generator_Database_Table_Column.php"]     = "Database/Table/Column.php";
        $archiveContents["Generator_Database_Table.php"]            = "Database/Table.php";
        $archiveContents["Generator_Template_DatabaseTemplate.php"] = "Template/DatabaseTemplate.php";
        $archiveContents["Generator_Template_TableTemplate.php"]    = "Template/TableTemplate.php";
        $archiveContents["Generator_Connection.php"]                = "Connection.php";
        $archiveContents["Generator_Database.php"]                  = "Database.php";
        $archiveContents["Generator_Formatter.php"]                 = "Formatter.php";
        $archiveContents["Generator_Template.php"]                  = "Template.php";
        $archiveContents["Generator_PharStub.php"]                  = "PharStub.php";

        try 
        {
            
            $phar = new \Phar($pharFile, 0, 'generator.phar');

            foreach ($archiveContents as $key => $path) 
            {
                $this->addFile($phar, $key, $path);
            }

            $phar->setSignatureAlgorithm(\Phar::SHA1);
            $phar->setStub($this->getStub());
            $phar->stopBuffering();

            unset($phar);

        } 
        catch (Exception $e) 
        {
            echo 'Write operations failed on brandnewphar.phar: ', $e;
        }

    }

    private function addFile($phar, $key, $file) 
    {
        $phar->addFile(__DIR__.DIRECTORY_SEPARATOR.$file, $key);
    }

    private function getStub() {

        return <<<'EOF'
#!/usr/bin/env php
<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 *
 * For the full copyright and license information, please view
 * the license that is located at the bottom of this file.
 */

Phar::mapPhar('generator.phar');
include 'phar://generator.phar/Generator_PharStub.php';
__HALT_COMPILER();
EOF;

    }

}