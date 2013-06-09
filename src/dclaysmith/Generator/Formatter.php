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
 * Provides a chainable method of formatting strings
 * @author D Clay Smith <dclaysmith@gmail.com>
 */
class Formatter
{
	/**
	 * @var string
	 */
	private $value;

	/**
	 * @var array
	 */
	private $pluralForms;

	/**
	 * @param array $pluralForms
	 * @return void
	 */
	public function __construct($pluralForms) {
		$this->setPluralForms($pluralForms);
	}

	/**
	 * @param array $pluralForms
	 * @return void
	 */
	public function setPluralForms($pluralForms) {
		$this->pluralForms = $pluralForms;
	}

	/**
	 * @param $value string
	 * @return void
	 */
	public function initialize($value) {
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function toString() 
	{
		return trim($this->value);
	}

	/**
	 * @param $search string
	 * @param $replace string
	 * @return Formatter $this
	 */
	public function replace($search, $replace) 
	{
		$this->value = str_replace($search,$replace,$this->value);
		return $this;
	}

	/**
	 * @param $search string
	 * @return Formatter $this
	 */
	public function strip($search)
	{
		$this->value = str_replace($search,"",$this->value);
		return $this;
	}

	/**
	 * @return Formatter $this
	 */
	public function toUpper()
	{
		$this->value = strtoupper($this->value);
		return $this;
	}

	/**
	 * @return Formatter $this
	 */
	public function toLower()
	{
		$this->value = strtolower($this->value);
		return $this;
	}

	/**
	 * @return Formatter $this
	 */
	public function toTitle()
	{
		$string = $this->value;
		$len 	= strlen($string);
		$i 		= 0;
		$last 	= "";
		$new 	= "";
		$string = strtoupper($string);
		while ($i<$len) {
			$char=substr($string,$i,1);
			if (ereg( "[A-Z]",$last)) {
				$new.=strtolower($char);
			} else {
				$new.=strtoupper($char);
			}
			$last=$char;
			$i++;
		}
		$this->value = $new;		
		return $this;
	}

	/**
	 * @return Formatter $this
	 */
	public function toHungarian()
	{
		$string = $this->value;
		$len=strlen($string);
		$i=0;
		$last= "";
		$new= "";
		$bFound = false;
		$string=strtoupper($string);
		while ($i<$len) {
			$char=substr($string,$i,1);
			if (preg_match( "[A-Z]", $last )) {
				$new.=strtolower($char);
			} else {
				if ($bFound) {
					$new.=strtoupper($char);
				} else {
					$new.=strtolower($char);
					$bFound = true;
				}
			}
			$last=$char;
			$i++;
		}
		$this->value = $new;
		return $this;
	}

	/**
	 * @return Formatter $this
	 */
	public function pluralize()
	{

		$string = $this->value;

		###############################################################
		# read the plural form file if we haven't already
		###############################################################
		// $aPluralForms = array();
		// if (count($aPluralForms) == 0) {

		// 	$sFilename	= dirname(__FILE__)."/Library/plural_forms.txt";

		// 	$oHandle 	= fopen($sFilename, "r");
		// 	while(!feof($oHandle)) {
		// 		$aValues = split("=",fgets($oHandle));
		// 		$aPluralForms[$aValues[0]] = $aValues[1];
		// 	}
		// }
		// foreach ($aPluralForms as $key => $value) {
		// 	if (strtolower($key) == strtolower($string)) {
		// 		$this->value = trim($value);
		// 		return $this;
		// 	}
		// }

		###############################################################
		# if we are talking about a 2 letter word, just add an s
		###############################################################
		if (strlen($string) < 3) {
			$this->value = $string;
			return $this;
		}

		###############################################################
		# if there were no exceptions named then apply standard rules
		###############################################################
		# check two letter endings
		###############################################################
		$sLastTwoCharacters = substr($string, -2);
		switch ($sLastTwoCharacters) {
			#######################################################
			# Where a noun ends in a sibilant sound
			#######################################################
			case "ss":
			case "sh":
			case "ch":
			case "se":
			case "ge":
				$this->value = substr($string,0,strlen($string)-1)."es";
				return $this;
				break;
			case "lf";
				$this->value = substr($string,0,strlen($string)-1)."ves";
				return $this;
				break;
			case "th";
				$this->value = $string."es";
				return $this;
				break;
			case "ry";
				$this->value = substr($string,0,strlen($string)-1)."ies";
				return $this;
				break;
		}

		###############################################################
		# there are a couple of cases where we are looking for a
		# consonant then another letter. use regex for this.
		###############################################################
		$pattern = "/(.*?[^aeiou])(\w{1})$/i";
		$matches = array();
		if (preg_match($pattern,$string,$matches)) {
			switch ($matches[2]) {
				case("s"):
					$this->value = $matches[0]."es";
					return $this;
					break;
				case("y"):
					$this->value = $matches[1]."ies";
					return $this;
					break;
				case("o"):
					$this->value = $matches[1]."oes";
					return $this;
					break;
			}
		}

		if (preg_match("/[xs]$/",$string)) {
			$this->value = $string . "es";
			return $this;
		}

		###############################################################
		# if we got here then i give up, just ad an "s"
		###############################################################
		$this->value = $string."s";
		return $this;
	}

}

?>