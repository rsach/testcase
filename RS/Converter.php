<?php

namespace RS;
use RS\Error;
use RS\Serial;

class Converter {

	public function __construct(Error $error) {
		$this->error = $error;

	}

	/**
	 * { format Request from controller to prepare it for processing }
	 *
	 * @param      <Request>  $request  The request
	 *
	 * @return     <string>  ( processed serial for controller  )
	 */
	public function processResponse($request) {

		$processedResponse;
		$formattedPattern = $this->giveMeFormatPattern($request->pattern);

		if ($formattedPattern instanceof FormattedPattern) {
			$processedResponse = $this->functionToCallAfterReceivingFormattedPattern($request, $formattedPattern);

		} else {
			$processedResponse = new ProcessedOutput(null, "failed", "pattern is not defined as required", 400);

		}

		return $processedResponse;

	}

	/**
	 * { It processed the serial according to the pre and post pattern }
	 *
	 * @param      <string>  $pattern  The pattern
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
	 * @param      <string>  $word   The word
	 *
	 * @return     <array>  Number of special character of pre and post pattern.
	 */
	public function countSpecialCharacter($formattedPattern) {

		// counting serial number values
		// if (strpos($word[0], ",")) {
		// 	$pre = $this->splitPrePatternWithDate($word[0]);

		// }

		$pre = $this->splitPatternWithSpecialCharacter($formattedPattern->prePattern);
		$post = $this->splitPatternWithSpecialCharacter($formattedPattern->postPattern);

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
	 * @param      <array>  $word    The word
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

	public function giveMeFormatPattern($pattern) {

		$validate = $this->error->naivePatternValidationLogic($pattern);

		if (!$validate) {
			return 'not a pattern';
		}
		$pattern = $this->splitPattern($pattern);
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

	public function functionToCallAfterReceivingFormattedPattern($request, $formattedPattern) {

		$formattedSerial = $this->giveMeFormatSerial($request->serial);

		$PatternConventionError = $this->error->patternSerialValidation($formattedPattern, $formattedSerial->serial);

		$dateConventionError = $this->error->dateValidationLogic($formattedPattern->date, $formattedSerial->date);

		if (!$PatternConventionError) {
			$processedResponse = new ProcessedOutput(null, "failed", "Pattern and Serial doesn't match according to the convention specified", 400);

		} else if (!$dateConventionError) {

			$processedResponse = new ProcessedOutput(null, "failed", "datePattern and date convention doesn't match", 400);

		} else {

			$processedSerial = $this->processSerial($formattedPattern, $formattedSerial->serial);
			$processedResponse = new ProcessedOutput($processedSerial, "success", "Pattern and serial convention is right", 200);

		}

		return $processedResponse;

	}

}