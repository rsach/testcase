<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RS\Converter;
use RS\Serial;

class SerialConverter extends Controller {

	public function __construct(Converter $converter) {
		$this->converter = $converter;

	}

	/**
	 * { function_description }
	 *
	 * @param      \App\Http\Requests\UrlRequest  $request  The request
	 *
	 * @return     <json>                         ( json response of the processedSerial according to the 																		pattern)
	 */
	public function converter(Request $request) {

		// validation

		$processedSerial = $this->converter->processResponse($request);

		// if ($errors > 0) {
		// 	$processedSerial = $errors;
		// }

		return response()->jsend(
			$data = $processedSerial->data,
			$presenter = null,
			$status = $processedSerial->status,
			$message = $processedSerial->message,
			$code = $processedSerial->code
		);

	}

}
