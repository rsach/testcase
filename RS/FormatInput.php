<?php

namespace RS;

use RS\Error;
use RS\FormattedPattern;
use RS\Serial;

/**
 *
 */
class FormatInput {

	public function __construct(Error $error) {
		$this->error = $error;

	}

	/**
	 * { it goes through a naivePatternValidation if it passes then it returns a string ,if not then
	 * 	 generates an object which have formattedPattern
	 * 	  }
	 *
	 * @param      <string>                   $pattern  The pattern
	 *
	 * @return     FormattedPattern|string  ( FormattedPattern have prePattern and postPattern ,
	 *                                        (date and order) are optional | string generates a
	 *                                        									'not a pattern'  )
	 */

	public function giveMeFormatPattern($pattern) {

		$validate = $this->error->naivePatternValidationLogic($pattern);

		if (!$validate) {
			return 'not a pattern';
		}
		$pattern = $this->splitPattern($pattern);

		if (count($pattern) < 2) {
			return 'not a pattern';

		}
		$prePattern = $pattern[0];
		$date = 'not a date';
		$order = 'DSC';
		if (strpos($prePattern, ",")) {

			$prePatternDateSplit = $this->splitPrePatternWithDate($pattern[0]);
			$prePattern = $prePatternDateSplit[0];
			$date = $prePatternDateSplit[1];
			$date = trim($date);

			$date = preg_split('/date:/', $date);
			$date = trim($date[1]);

		} else {

			$prePattern = trim($pattern[0]);

		}

		if (count($pattern) > 2) {
			$order = $pattern[2];

		}

		$postPattern = trim($pattern[1]);

		return new FormattedPattern($prePattern, $postPattern, $date, $order);

	}

	/**
	 * { it's generating a serial object from a serial string  }
	 *
	 * @param      <string>  $serialUrl  The serial url
	 *
	 * @return     Serial  ( it's a serial object which have attributes like serial and date );
	 */

	public function giveMeFormatSerial($serialUrl) {

		$date = 'not a date';

		if (strpos($serialUrl, ",")) {

			$serialUrl = $this->splitPrePatternWithDate($serialUrl);
			$serial = $serialUrl[0];
			$serial = trim($serial);
			$date = $serialUrl[1];
			$date = trim($date);

		} else {

			$serial = trim($serialUrl);

		}

		return new Serial($serial, $date);

	}

	/**
	 * Splits a pattern.
	 *
	 * @param      <string>  $word   The word
	 *
	 * @return     <array>  ( split the pattern into pre and post )
	 */

	public function splitPattern($word) {

		return preg_split("/[=>{}]+/", $word);

	}

	/**
	 * Splits a pre pattern with date.
	 *
	 * @param      <array>  $word   The word
	 *
	 * @return     <array>  ( split the pre pattern with the date)
	 */

	public function splitPrePatternWithDate($word) {
		return preg_split('/,/', $word);

	}

}