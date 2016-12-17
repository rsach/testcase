<?php

namespace RS;

class Error {

	public function patternSerialValidation($formattedPattern, $serial) {

		//	$flag = false;

		$flag = $this->patternValidationLogic($formattedPattern->prePattern, $serial);

		return $flag;
	}

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

	public function dateValidationLogic($datePattern, $date) {
		return false;

	}

}