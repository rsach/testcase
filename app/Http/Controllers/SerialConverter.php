<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RS\Converter;

class SerialConverter extends Controller {

	public function __construct(Converter $converter) {
		$this->converter = $converter;

	}

	/**
	 * { function_description }
	 *
	 * @param      \App\Http\Requests\UrlRequest  $request  The request
	 *
	 * @return     <json>                         ( json response of the processedOutput according to the 																		pattern)
	 */
	public function converter(Request $request) {

		// validation

		$processedOutput = $this->converter->processResponse($request);

		return response()->jsend(
			$data = $processedOutput->data,
			$presenter = null,
			$status = $processedOutput->status,
			$message = $processedOutput->message,
			$code = $processedOutput->code
		);

	}

}
