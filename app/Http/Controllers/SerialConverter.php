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
		//
		$this->validate($request, [
			'pattern' => '[0-9]+',
		]);

		$processedSerial = $this->converter->formatRequest($request);

		return response()->jsend(
			$data = $processedSerial,
			$presenter = null,
			$status = 'success',
			$message = 'This is a Jsend Response ',
			$code = 200
		);

	}

}
