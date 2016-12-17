<?php

namespace RS;

class Error {

	public function patternSerialValidation($pattern, $serial) {

		$flag = false;

		$pattern = preg_split("/[=>{}\s]+/", $pattern);
		$prePattern = $pattern[0];
		if (strpos($prePattern, ",")) {
			$prePattern = preg_split("/,/", $prePattern);
			$prePattern = $prePattern[0];
		}

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

					return false;
					break;
				}
			}

		} else {
			return false;
		}

		return $flag;
	}

}