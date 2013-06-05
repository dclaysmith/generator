<?php
Namespace dclaysmith\Generator\Template;

use dclaysmith\Generator\Template;
use dclaysmith\Generator\Formatter;

class DataObjectTemplate extends Template {

	public $tables;

	//tbl_p_product
	public function filename($tableName) {
		$formatter = new Formatter($tableName);
		return $formatter->replace("tbl_p_","boP")
						->replace("tbl_c_","boC")
						->toTitle()
						->pluralize()
						->toString();
	}

	private function toClassName($tableName) {
		$formatter = new Formatter($tableName);
		return $formatter->replace("tbl_p_","boP")
						->replace("tbl_c_","boC")
						->toTitle()
						->pluralize()
						->toString();
	}

	/**
	 * generate
	 */
	public function generate(\dclaysmith\Generator\Database\Table $table) {

		$sTemplateVariable = str_replace(array('tbl_c_','tbl_p_'),'',$table->name);

		$sTemplateClass = str_replace(array('tbl_c_','tbl_p_'),'',$table->name);
		$sTemplateClass = ucwords(str_replace(array("-","_"),array("- ","_ "),$sTemplateClass));
		$sTemplateClass = ucwords(str_replace(array("- ","_ "),array("-","_"),$sTemplateClass));
		$sTemplateClass = str_replace(' ','',ucwords(str_replace('_',' ',$sTemplateClass)));
		$sTemplateClass = str_replace('-','_',$sTemplateClass);

		$aOutput[] = <<<EOF

<?php
/**
 * abstract class {$sTemplateClass}_Base
 *
 * This class defines an OO representation of the {$table->name} table
 *
 * NOTE: This file is autogenerated from the {$table->name} using
 * the CG2K6.
 *
 * IMPORTANT: This file should NEVER BE CHANGED BY HAND. NEVER! Any changes
 * that need to be made should be made to the template file and replicated for
 * all tables.
 *
 * USAGE: This is an abstract class and thus it must be inherited. Create a
 * or modify business object to use this file. Business Objects are stored in
 * the "/bo" directory.
 *
 * PHP version 5
 *
 * @category   Data Object
 * @author     D CLAY SMITH
 * @copyright  2007 D Clay Smith
 */
if (!defined('MEMCACHE_ENABLED')) define('MEMCACHE_ENABLED', 'false');

abstract class {$sTemplateClass}_Base extends CDataObject {

	// CONSTRUCTOR
	function __construct() {
	}

	// VARIABLES
EOF;
		// add in a variable for each column
		foreach ($table->columns() as $column) {
			$aOutput[] = "\tprotected \$_".str_replace(' ','',ucwords(str_replace('_',' ',str_replace('tbl_c_','',$column->name)))).";";
		}

		// add in a variable to store relationship
		foreach ($this->tables as $table) {
			foreach ($table->columns() as $column) {
				if ($column->name == str_replace("tbl_p_", "", $table->name)."_id") {
					$sTableClass = str_replace(array('tbl_c_','tbl_p_'),'',$table->name);
					$sTableClass = ucwords(str_replace(array("-","_"),array("- ","_ "),$sTableClass));
					$sTableClass = ucwords(str_replace(array("- ","_ "),array("-","_"),$sTableClass));
					$sTableClass = str_replace(' ','',ucwords(str_replace('_',' ',$sTableClass)));
					$sTableClass = str_replace('-','_',$sTableClass);

					$aOutput[] = "\tprotected \$_col".$sTemplateClass.$this->pluralize($sTableClass).";";
				}
			}
		}
		foreach ($table->columns() as $column) {
			foreach ($this->tables as $table) {
				if ($column->name == str_replace("tbl_p_", "", $table->name)."_id") {

					$sColumnClass = str_replace('_id','',$column->name);
					$sColumnClass = ucwords(str_replace(array("-","_"),array("- ","_ "),$sColumnClass));
					$sColumnClass = ucwords(str_replace(array("- ","_ "),array("-","_"),$sColumnClass));
					$sColumnClass = str_replace(' ','',ucwords(str_replace('_',' ',$sColumnClass)));
					$sColumnClass = str_replace('-','_',$sColumnClass);

					$aOutput[] = "\tprotected \$_o".$sColumnClass.";";
				}
			}
		}		

		$aOutput[] = <<<EOF


	// PROPERTIES
EOF;

		foreach ($table->columns() as $column) {
			switch ($column->name) {
				case "id":
				case "date_entered":
				case "date_modified":
				case "ts":
					continue;
					break;
				default:
					$sMethodName 	= str_replace(' ','',ucwords(str_replace('_',' ',str_replace('tbl_c_','',$column->name))));
					$sVariableName 	= str_replace(' ','',ucwords(str_replace('_',' ',str_replace('tbl_c_','',$column->name))));

		$aOutput[] = <<<EOF
	public function get{$sMethodName}() {
EOF;
					if ($column->type == "tinyint") {
		$aOutput[] = <<<EOF
		return (\$this->{$sVariableName}) ? 1 : 0;
EOF;
					} else {
		$aOutput[] = <<<EOF
		return \$this->_{$sVariableName};
EOF;
					}
		$aOutput[] = <<<EOF
	}
	public function set{$sMethodName}(\$value) {
EOF;
		switch ($column->type) {
			case "double":
				$aOutput[] = <<<EOF
		if (!is_numeric(\$value)) {
			throw new exception('Non-numeric value provided for set{$sMethodName}.');	
		}					
EOF;
				break;
			case "int":
			case "bigint":
				$aOutput[] = <<<EOF
		if (!is_int(\$value)) {
			throw new exception('Non-integer value provided for set{$sMethodName}.');	
		}	
EOF;
				break;
			case "tinyint":
				$aOutput[] = <<<EOF
		if (!(\$value === true || \$value === false)) {
			throw new exception('Non-boolean value provided for set{$sMethodName}.');	
		}		
EOF;
				break;
			case "datetime":
				$aOutput[] = <<<EOF
		if (!CValidation::isValidDatetime(\$value)) {
			throw new exception('Non-date value provided for set{$sMethodName}.');	
		}		
EOF;
				break;
			case "varchar":	
				$aOutput[] = <<<EOF
		if (strlen(stripslashes(\$value)) > {$column->length}) {
			throw new exception('The value provided for set{$sMethodName} exceeds the allowed length of {$column->length}.');				
		} 			
EOF;
				break;
		}
		
		$aOutput[] = <<<EOF

		if (\$this->_{$sVariableName} != \$value) {
			if (!\$this->getIsNew()) \$this->_aChanged[] = "{$sMethodName}";
			\$this->_isDirty = true;
		}
		\$this->_{$sVariableName} = \$value;
		return true;
	}
EOF;
					break;
			}
		}

		foreach ($table->columns() as $column) {
			foreach ($this->tables as $table) {
				if ($column->name == str_replace("tbl_p_", "", $table->name)."_id") {

					$sColumnClass = str_replace(array('tbl_c_','tbl_p_'),'',$column->name);
					$sColumnClass = str_replace('_id','',$sColumnClass);
					$sColumnClass = ucwords(str_replace(array("-","_"),array("- ","_ "),$sColumnClass));
					$sColumnClass = ucwords(str_replace(array("- ","_ "),array("-","_"),$sColumnClass));
					$sColumnClass = str_replace(' ','',ucwords(str_replace('_',' ',$sColumnClass)));
					$sColumnClass = str_replace('-','_',$sColumnClass);

					$aOutput[] = <<<EOF

	public function get{$sColumnClass}() {
		if (\$this->_o{$sColumnClass} == null && \$this->get{$sColumnClass}Id() > 0) {			
			if (!\$this->_o{$sColumnClass} = {$sColumnClass}_Eng::get(\$this->get{$sColumnClass}Id(),false)) {
				throw new exception('Unable to retrieve the {$sColumnClass}.');			
			}
		}
		return \$this->_o{$sColumnClass};
	}
EOF;

				}
			}
		}	

		foreach ($this->tables as $table) {
			foreach ($table->columns() as $column) {
				if ($column->name == str_replace("tbl_p_", "", $table->name)."_id") {

					$sTableClass = str_replace(array('tbl_c_','tbl_p_'),'',$table->name);
					$sTableClass = str_replace('_id','',$sTableClass);
					$sTableClass = ucwords(str_replace(array("-","_"),array("- ","_ "),$sTableClass));
					$sTableClass = ucwords(str_replace(array("- ","_ "),array("-","_"),$sTableClass));
					$sTableClass = str_replace(' ','',ucwords(str_replace('_',' ',$sTableClass)));
					$sTableClass = str_replace('-','_',$sTableClass);


					$aOutput[] = "\tprotected \$_col".$sTemplateClass.$this->pluralize($sTableClass).";";


					$aOutput[] = <<<EOF

	public function get{$sTemplateClass}{$this->pluralize($sTableClass)}() {
		if (\$this->_col{$sTemplateClass}{$this->pluralize($sTableClass)} == null) {
			\$aParams = array(array("","{$column->name}","=",\$this->getId()));
			if (!\$this->_col{$sTemplateClass}{$this->pluralize($sTableClass)} = {$sTableClass}_Eng::search(\$aParams)) {
				throw new exception('Unable to retrieve the _col{$sTemplateClass}{$this->pluralize($sTableClass)}.');
			}
		}
		return \$this->_col{$sTemplateClass}{$this->pluralize($sTableClass)};
	}
EOF;

				}
			}
		}		

		$aOutput[] = <<<EOF

	public function add() {

		// check for null values
		\$aNullValues = array();
EOF;
		foreach ($table->columns() as $column) {
			switch ($column->name) {
				case "id": case "ts": case "date_entered": case "date_modified": case "uuid":
					break;
				default:
					if (!$column->nullable) {

						$sColumnClass = ucwords(str_replace(array("-","_"),array("- ","_ "),$column->name));
						$sColumnClass = ucwords(str_replace(array("- ","_ "),array("-","_"),$sColumnClass));
						$sColumnClass = str_replace(' ','',ucwords(str_replace('_',' ',$sColumnClass)));
						$sColumnClass = str_replace('-','_',$sColumnClass);

						switch ($column->type) {
							case "int": case "bigint":
								$aOutput[] = "\t\tif (!is_int(\$this->get{$sColumnClass}())) {";
								break;
							case "tinyint":
								$aOutput[] = "\t\tif (!(\$this->get{$sColumnClass}() == true || \$this->get{$sColumnClass}() == false)) {";		
								break;
							case "datetime":
								$aOutput[] = "\t\tif (!CValidation::isValidDatetime(\$this->get{$sColumnClass}())) {";
								break;
							default:
								$aOutput[] = "\t\tif (strlen(\$this->get{$sColumnClass}()) < 1) {";		
								break;
						}
						
						$sLabel = ucwords(str_replace('_',' ',$column->name));

						$aOutput[] = "\t\t\t\$aNullValues[] = \"{$sLabel}\";";
						$aOutput[] = "\t\t}";
					}
					break;
			}
		}

		$aOutput[] = <<<EOF

		if (count(\$aNullValues) > 0) {
			throw new exception('Insert failed. Null values were provided for the following non-null fields: '.implode(", ",\$sNullFields);
		}

		\$sUuid = CFunctions::getUuid('{$sTemplateVariable}');
EOF;
		$aFields = array();
		foreach ($table->columns() as $column) {
			switch ($column->name) {
				case "id": case "ts":
					break;
				default:	
					$key = str_replace(' ','',ucwords(str_replace('_',' ',str_replace('tbl_c_','',$column->name))));						
					$aFields[$key] = $column->name;
					break;
			}
		}		

		$fields = "`".implode("`,`",$aFields)."`";
		$values = "':".implode("',':",$aFields)."'";

        $aOutput[] = "\t\t\$aSql = array();";
        $aOutput[] = "\t\t\$aSql[] = \"INSERT INTO\";";
        $aOutput[] = "\t\t\$aSql[] = \"`{$table->name}`\";";
        $aOutput[] = "\t\t\$aSql[] = \"($fields)\";";
        $aOutput[] = "\t\t\$aSql[] = \"VALUES\";";
        $aOutput[] = "\t\t\$aSql[] = \"($values)\";";

        $aOutput[] = "\t\t\$statement = \$this->getDbManager->prepare(implode(\" \",\$aSql));";

        foreach ($aFields as $variable => $field) {
        	if ($field == "uuid") {
        		$aOutput[] = "\t\t\$statement->bindParam(\":{$field}\",'\$sUuid');";
        	} else {
        		$aOutput[] = "\t\t\$statement->bindParam(\":{$field}\",'\$this->_".$variable."');";
        	}
        }

        $aOutput[] = "\t\t\$statement->execute();";

        $aOutput[] = <<<EOF

		if (!\$id = \$this->getDbManager()->lastInsertId('id')) {
			return false;
		} else {
			\$this->setId(\$id);
			\$this->setIsNew(false);
			\$this->setIsDirty(false);
			if (property_exists(\$this, '_Uuid')) \$this->setUuid(\$sUuid);
			return \$this;
		}
	}

	public function update() {

		if (MEMCACHE_ENABLED) CMemcache::delete("object_{$sTemplateVariable}_".md5(\$this->getId()));

		\$aNullValues = array();
EOF;

		foreach ($table->columns() as $column) {
			switch ($column->name) {
				case "id": case "ts": case "date_entered": case "date_modified": case "uuid":
					break;
				default:
					if (!$column->nullable) {

						$sColumnClass = ucwords(str_replace(array("-","_"),array("- ","_ "),$column->name));
						$sColumnClass = ucwords(str_replace(array("- ","_ "),array("-","_"),$sColumnClass));
						$sColumnClass = str_replace(' ','',ucwords(str_replace('_',' ',$sColumnClass)));
						$sColumnClass = str_replace('-','_',$sColumnClass);

						switch ($column->type) {
							case "int": case "bigint":
								$aOutput[] = "\t\tif (!is_int(\$this->get{$sColumnClass}())) {";
								break;
							case "tinyint":
								$aOutput[] = "\t\tif (!(\$this->get{$sColumnClass}() == true || \$this->get{$sColumnClass}() == false)) {";		
								break;
							case "datetime":
								$aOutput[] = "\t\tif (!CValidation::isValidDatetime(\$this->get{$sColumnClass}())) {";
								break;
							default:
								$aOutput[] = "\t\tif (strlen(\$this->get{$sColumnClass}()) < 1) {";		
								break;
						}
						
						$sLabel = ucwords(str_replace('_',' ',$column->name));

						$aOutput[] = "\t\t\t\$aNullValues[] = \"{$sLabel}\";";
						$aOutput[] = "\t\t}";
					}
					break;
			}
		}

		$aOutput[] = <<<EOF

		if (count(\$aNullValues) > 0) {
			throw new exception('Insert failed. Null values were provided for the following non-null fields: '.implode(", ",\$sNullFields);
		}
EOF;
		$aFields = array();
		foreach ($table->columns() as $column) {
			switch ($column->name) {
				case "id": case "ts":
					break;
				default:	
					$key = str_replace(' ','',ucwords(str_replace('_',' ',str_replace('tbl_c_','',$column->name))));						
					$aFields[$key] = $column->name;
					break;
			}
		}



		$fields = "`".implode("`,`",$aFields)."`";
		$values = "':".implode("',':",$aFields)."'";

        $aOutput[] = "\t\t\$aSql = array();";
        $aOutput[] = "\t\t\$aSql[] = \"UPDATE\";";
        $aOutput[] = "\t\t\$aSql[] = \"`{$table->name}`\";";
        $aOutput[] = "\t\t\$aSql[] = \"SET\";";

        foreach ($aFields as $variable => $field) {
        	$aOutput[] = "\t\t\$aSql[] = \"`$field` = ':$field'\";";
        }
        $aOutput[] = "\t\t\$aSql[] = \"WHERE\";";
        $aOutput[] = "\t\t\$aSql[] = \"`id` = :id\";";

        foreach ($aFields as $key => $value) {

        }


        $aOutput[] = "\t\t\$statement = \$this->getDbManager->prepare(implode(\" \",\$aSql));";

        foreach ($aFields as $variable => $field) {
        	if ($field == "uuid") {
        		$aOutput[] = "\t\t\$statement->bindParam(\":{$field}\",\$sUuid);";
        	} else {
        		$aOutput[] = "\t\t\$statement->bindParam(\":{$field}\",\$this->_".$variable.");";
        	}
        }

        $aOutput[] = "\t\t\$statement->bindParam(\":id\",\$this->getId());";
        $aOutput[] = "\t\t\$statement->execute();";

		$aOutput[] = <<<EOF

		if (!\$this->getDbManager()->run_sql(\$sql)) {
			return false;
		} else {
			return \$this;
		}
	}

	public function delete() {

		if (MEMCACHE_ENABLED) CMemcache::delete("object_{$sTemplateVariable}_".md5(\$this->getId()));
EOF;

		foreach ($this->tables as $table) {

		}

		$aOutput[] = <<<EOF


<[declare \$sChildEngine = ""]>
<[\$sTmp = "tbl_c_".format(\$sClassName,"LOWER")]>
<[foreach \$table in \$cgDatabase.tables(3|\$sTmp)]>
<[\$sChildEngine = format(\$table.name,"r('tbl_c_':''),SENTENCE")]>
<[\$sChildEngine = format(\$sChildEngine,"r('_':'')")]>
<[\$sChildEngine = format(\$sChildEngine,"r('-':'_')")]>	
		\t(3)c<[print(\$sChildEngine)]>_Eng::deleteWhere(array(array("","<[print(\$sAsVariable)]>_id","=",\$this->getId())));\n
<[next]>

<[foreach \$table in \$cgDatabase.tables(2|\$cgTable.name|"tbl_p_"|"_id")]>
<[if("tbl_p_" IN \$table.name)]>
	<[\$sTmp = format(\$table.name,"r('tbl_p_':''),SENTENCE")]>
	<[\$sTmp = format(\$sTmp,"r('_':'')")]>
	<[\$sTmp = format(\$sTmp,"r('-':'_')")]>
		\t(3)<[print(\$sTmp)]>_Eng::deleteWhere(array(array("","<[print(\$sAsVariable)]>_id","=",\$this->getId())));\n	
<[endif]>
<[next]>

		\$sql = "DELETE FROM `<[print(\$cgTable.name)]>` WHERE id = ".\$this->getId();
		if (!\$this->getDbManager()->run_sql(\$sql)) {
			return false;
		} else {
			return true;
		}

	}
}
?>
EOF;





		return implode("\n",$aOutput);
	}

}
?>