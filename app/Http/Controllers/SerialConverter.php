<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RS\ProcessOutput;

class SerialConverter extends Controller {

	public function __construct(ProcessOutput $processOutput) {
		$this->processOutput = $processOutput;

	}

	/**
	 * { function_description }
	 *
	 * @param      \App\Http\Requests\UrlRequest  $request  The request
	 *
	 * @return     <json>                         ( json response of the processedOutput according to the 																		pattern)
	 */
	public function processOutput(Request $request) {

		// validation

		$processedOutput = $this->processOutput->processResponse($request);

		return response()->jsend(
			$data = $processedOutput->data,
			$presenter = null,
			$status = $processedOutput->status,
			$message = $processedOutput->message,
			$code = $processedOutput->code
		);

	}

}
