<?php

namespace RS;
use RS\Error;
use RS\Serial;

class Converter {

	/**
	 * { it uses dependency injection to use Error class for validation }
	 *
	 * @param      \RS\Error  $error  The error
	 */

	/**
	 * { It processed the serial according to the pre and post pattern }
	 *
	 * @param      <FormattedPattern>  $formattedPattern  The formattedPattern
	 * @param      <string>  $serial   The serial
	 *
	 * @return     <string>  ( alter the serial according to the pre and post pattern )
	 */

	public function processSerial($formattedPattern, $serial) {

		//$word = $this->splitPattern($pattern);

		$count = $this->countSpecialCharacter($formattedPattern);

		$serial1 = str_split($serial);

		$mod = array();

		foreach ($count[1] as $key => $value) {

			$mod = array_merge($mod, $this->alterSerial($serial1, $formattedPattern, $count, $key));

		}

		$serial = $this->splitPatternWithSpecialCharacter($formattedPattern->postPattern);

		$serial = $serial[0] . implode("", $mod);

		return $serial;

	}

	/**
	 * Counts the number of special character.
	 *
	 * @param      <FormattedPattern>  $formattedPattern   The formattedPattern
	 *
	 * @return     <array>  Number of special character of pre and post pattern.
	 */

	public function countSpecialCharacter($formattedPattern) {

		$pre = $this->splitPatternWithSpecialCharacter($formattedPattern->prePattern);
		$post = $this->splitPatternWithSpecialCharacter($formattedPattern->postPattern);

		$countPre = array_count_values(str_split($pre[1]));
		$countPost = array_count_values(str_split($post[1]));

		return array($countPre, $countPost);

	}

	/**
	 * Calculates the offset.
	 *
	 * @param      <FormattedPattern>   $formattedPattern      The formattedPattern
	 * @param      integer  $start      The start
	 * @param      integer  $preCount   The pre count
	 * @param      integer  $postCount  The post count
	 *
	 * @return     integer  The offset.
	 */

	public function calculateOffset($formattedPattern, $start, $preCount, $postCount) {
		$offset = $start + ($preCount - $postCount);

		if ($formattedPattern->order == 'ASC') {
			$offset = $start;

		} else if ($formattedPattern->order == 'DSC') {
			$offset = $offset;

		}

		return $offset;

	}

	/**
	 * { it alter the serial for the current special character of the iteration }
	 *
	 * @param      <array>  $serial  The serial
	 * @param      <FormattedPattern>  $formattedPattern    The formattedPattern
	 * @param      <Associative array>  $count   The count
	 * @param      <string>  $key     The key is the special character processing at a time
	 *
	 * @return     <array>  ( it returns the processed serial for that special character )
	 */

	public function alterSerial($serial, $formattedPattern, $count, $key) {

		$hashStart = strpos($formattedPattern->prePattern, $key);

		$offset = $this->calculateOffset($formattedPattern, $hashStart, $count[0][$key], $count[1][$key]);

		$mod = array_splice($serial, $offset, $count[1][$key]);

		return $mod;

	}

	/**
	 * Splits a pattern with special character.
	 *
	 * @param      <array>  $word   The word
	 *
	 * @return     <array>  ( splits alphabets and special characters  )
	 */

	public function splitPatternWithSpecialCharacter($word) {
		return preg_split('/(?<=[A-Z])(?=\W)/', $word);
	}

}