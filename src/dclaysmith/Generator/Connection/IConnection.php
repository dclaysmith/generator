<?php
/*
 * This file is part of Generator.
 *
 * (c) D Clay Smith <dclaysmith@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dclaysmith\Generator\Connection;

interface IConnection 
{

  function getTables();
  function getColumns($table_name);
  function getRows($table_name);

}

?>