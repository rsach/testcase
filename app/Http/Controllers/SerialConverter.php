<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
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
	public function converter(UrlRequest $request) {

		// validation
		//
		//

		$num = $this->converter->formatRequest($request);

		return response()->jsend(
			$data = $num,
			$presenter = null,
			$status = 'success',
			$message = 'This is a Jsend Response ',
			$code = 200
		);

	}

}
