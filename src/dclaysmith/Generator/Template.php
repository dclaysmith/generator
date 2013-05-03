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

class Template {

	###########################################################################
	# pluralize()
	###########################################################################
	# pluralize the word. first look for exceptions, then apply accepted rules
	###########################################################################
	function pluralize($string,$aPluralForms=null) {

		###############################################################
		# read the exception file if we haven't already
		###############################################################
		if (count($aPluralForms) == 0) {

			$sFilename	= dirname(__FILE__)."/Library/plural_forms.txt";

			$oHandle 	= fopen($sFilename, "r");
			while(!feof($oHandle)) {
				$aValues = split("=",fgets($oHandle));
				$aPluralForms[$aValues[0]] = $aValues[1];
			}
		}

		###############################################################
		# first see if an exception applies
		###############################################################
		foreach ($aPluralForms as $key => $value) {
			if (strtolower($key) == strtolower($string)) {
				return trim($value);
			}
		}

		###############################################################
		# if we are talking about a 2 letter word, just add an s
		###############################################################
		if (strlen($string) < 3) {
			return $string;
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
				return substr($string,0,strlen($string)-1)."es";
				break;
			case "lf";
				return substr($string,0,strlen($string)-1)."ves";
				break;
			case "th";
				return $string."es";
				break;
			case "ry";
				return substr($string,0,strlen($string)-1)."ies";
				break;
		}

		###############################################################
		# there are a couple of cases where we are looking for a
		# consonant then another letter. use regex for this.
		###############################################################
		$pattern = "/(.*?[^aeiou])(\w{1})$/i";
		if (preg_match($pattern,$string,$matches)) {
			switch ($matches[2]) {
				case("s"):
					return $matches[0]."es";
					break;
				case("y"):
					return $matches[1]."ies";
					break;
				case("o"):
					return $matches[1]."oes";
					break;
			}
		}

		###############################################################
		# if we got here then i give up, just ad an "s"
		###############################################################
		return $string."s";
	}

}
?>