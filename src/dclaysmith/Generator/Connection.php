<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
Namespace dclaysmith\Generator;

interface Connection {

  function getTables();
  function getColumns($table_name);
  function getRows($table_name);

}

?>