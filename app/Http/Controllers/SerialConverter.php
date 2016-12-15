<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Serial;

class SerialConverter extends Controller {

	/**
	 * { function_description }
	 *
	 * @param      \App\Http\Requests\UrlRequest  $request  The request
	 *
	 * @return     <json>                         ( json response of the processedSerial according to the 																		pattern)
	 */
	public function converter(UrlRequest $request) {

		// validation

		$ser = new Serial($request->pattern, $request->serial, $request->date);

		$num = $this->processSerial($ser->pattern, $ser->serial);

		return response()->jsend(
			$data = $num,
			$presenter = null,
			$status = 'success',
			$message = 'This is a Jsend Response ',
			$code = 200
		);

	}

	/**
	 * { It processed the serial according to the pre and post pattern }
	 *
	 * @param      <string>  $pattern  The pattern
	 * @param      <string>  $serial   The serial
	 *
	 * @return     <string>  ( alter the serial according to the pre and post pattern )
	 */
	public function processSerial($pattern, $serial) {

		$word = $this->splitPattern($pattern);

		$count = $this->countSpecialCharacter($word);

		$serial1 = str_split($serial);

		$mod = array();

		foreach ($count[1] as $key => $value) {

			$mod = array_merge($mod, $this->alterSerial($serial1, $word, $count, $key));

		}

		$serial = $this->splitPatternWithSpecialCharacter($word[1]);

		$serial = $serial[0] . implode("", $mod);

		return $serial;

	}
	/**
	 * Counts the number of special character.
	 *
	 * @param      <string>  $word   The word
	 *
	 * @return     <array>  Number of special character of pre and post pattern.
	 */
	public function countSpecialCharacter($word) {

		// counting serial number values
		if (strpos($word[0], ",")) {
			$pre = $this->splitPrePatternWithDate($word[0]);

		}

		$pre = $this->splitPatternWithSpecialCharacter($word[0]);
		$post = $this->splitPatternWithSpecialCharacter($word[1]);

		$countPre = array_count_values(str_split($pre[1]));
		$countPost = array_count_values(str_split($post[1]));

		return array($countPre, $countPost);

	}
	/**
	 * Calculates the offset.
	 *
	 * @param      <string>   $order      The order
	 * @param      integer  $start      The start
	 * @param      integer  $preCount   The pre count
	 * @param      integer  $postCount  The post count
	 *
	 * @return     integer  The offset.
	 */
	public function calculateOffset($order, $start, $preCount, $postCount) {
		$offset = $start + ($preCount - $postCount);

		if (count($order) > 2) {
			if ($order[2] == 'ASC') {
				$offset = $start;

			} else if ($order[2] == 'DSC') {
				$offset = $offset;

			}

		}

		return $offset;

	}
	/**
	 * { it alter the serial for the current special character of the iteration }
	 *
	 * @param      <array>  $serial  The serial
	 * @param      <array>  $word    The word
	 * @param      <Associative array>  $count   The count
	 * @param      <string>  $key     The key is the special character processing at a time
	 *
	 * @return     <array>  ( it returns the processed serial for that special character )
	 */
	public function alterSerial($serial, $word, $count, $key) {

		$hashStart = strpos($word[0], $key);

		$offset = $this->calculateOffset($word, $hashStart, $count[0][$key], $count[1][$key]);

		$mod = array_splice($serial, $offset, $count[1][$key]);

		return $mod;

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
