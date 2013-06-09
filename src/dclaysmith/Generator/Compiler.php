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

        $archiveContents[] = "Connection/IConnection.php";
        $archiveContents[] = "Connection/MySql.php";
        $archiveContents[] = "Database/Column.php";
        $archiveContents[] = "Database/Table.php";
        $archiveContents[] = "Template/DatabaseTemplate.php";
        $archiveContents[] = "Template/TableTemplate.php";
        $archiveContents[] = "Connection.php";
        $archiveContents[] = "Database.php";
        $archiveContents[] = "Formatter.php";
        $archiveContents[] = "Template.php";
        $archiveContents[] = "Application.php";
        $archiveContents[] = "../Generator.php";

	    $phar = new \Phar($pharFile, 0, 'generator.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA1);
        foreach ($archiveContents as $path) 
        {
        	$this->addFile($phar, $path);
        }

        $phar->setStub($this->getStub());
        $phar->stopBuffering();
        unset($phar);
    }

    private function addFile($phar, $file) 
    {
        $phar->addFile(__DIR__.DIRECTORY_SEPARATOR.$file, $file);
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
include 'phar://generator.phar/Application.php';
__HALT_COMPILER();
EOF;

    }

}