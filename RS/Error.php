<?php

namespace RS;

class Error {

	/**
	 * { it fetches the formattedPattern and serial from the conveter class and
	 * 	 returns a boolean value for validation }
	 *
	 * @param      <FormattedPattern>  $formattedPattern  The formatted pattern
	 * @param      <string>  $serial            The serial
	 *
	 * @return     <boolean>  ( validation value )
	 */

	public function patternSerialValidation($formattedPattern, $serial) {

		//	$flag = false;

		$flag = $this->patternValidationLogic($formattedPattern->prePattern, $serial);

		return $flag;
	}

	/**
	 * { it uses prepattern and serial to validate , first it checks the length of the
	 * 	 prepattern and serial ,after it checks character by character then it returns boolean value for
	 * 	 validation }
	 *
	 * @param      <string>   $prePattern  The pre pattern
	 * @param      <string>   $serial      The serial
	 *
	 * @return     boolean  ( validation value for the logic )
	 */

	public function patternValidationLogic($prePattern, $serial) {
		if (strlen($prePattern) == strlen($serial)) {
			$flag = true;

			for ($i = 0; $i < strlen($prePattern); $i++) {

				if ($prePattern[$i] == $serial[$i]) {
					$flag = true;
				} else if ($prePattern[$i] == "#") {
					$flag = preg_match("/[0-9]/", $serial[$i]);

				} else if ($prePattern[$i] == "%") {
					$flag = preg_match("/[A-Z]/", $serial[$i]);

				} else {
					$flag = false;

				}

				if (!$flag) {

					$flag = false;
					break;
				}
			}

		} else {
			return false;
		}

		return $flag;

	}

	/**
	 * { it uses datePattern and date values to validate the date logic   }
	 *
	 * @param      <string>   $datePattern  The date pattern
	 * @param      <string>   $date         The date
	 *
	 * @return     boolean  ( boolean for date validation )
	 */

	public function dateValidationLogic($datePattern, $date) {
		$flag = false;
		for ($i = 0; $i < strlen($date); $i++) {
			if ($datePattern[$i] == $date[$i]) {
				$flag = true;

			} else if ($datePattern[$i] == "?") {
				$flag = preg_match("/\d/", $date[$i]);
			} else {
				$flag = false;

			}

			if (!$flag) {
				break;
			}
		}

		return $flag;

	}

	/**
	 * { it uses pattern to validate whether it is defined as required }
	 *
	 * @param      <string>  $pattern  The pattern
	 *
	 * @return     <boolean>  ( boolean for naivePatternLogic )
	 */

	public function naivePatternValidationLogic($pattern) {
		return strpos($pattern, '=>');

	}

}