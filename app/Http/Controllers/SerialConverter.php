<?php

namespace App\Http\Controllers;

use App\Serial;
use Illuminate\Http\Request;

class SerialConverter extends Controller {

	public function converter(Request $request) {

		// pattern fetching
		// processing the serial string
		// $data , $status , $message , $code fetching the values for these variable
		// count the # $ ? and seperate them for processing
		//

		$pattern = $request->pattern;
		$serial = $request->serial;
		$date = $request->date;

		$ser = new Serial($serial, $date);

		$num = $this->processSerial($pattern, $serial);

		return response()->jsend(
			$data = $num,
			$presenter = null,
			$status = 'success',
			$message = 'This is a Jsend Response ',
			$code = 200
		);

	}

	public function processSerial($pattern, $serial) {

		$count = $this->counta($pattern);
		return $count[1]["#"];

	}

	public function counta($word) {

		// counting serial number values
		$word = preg_split("/[=>\s]+/", $word);

		$pre = $this->splita($word[0]);
		$post = $this->splita($word[1]);

		$countPre = array_count_values(str_split($pre[1]));
		$countPost = array_count_values(str_split($post[1]));

		// return preg_split('/(?<=[A-Z])(?=\W)/', $word);
		//
		//
		return array($countPre, $countPost);

	}

	public function splita($word) {
		return preg_split('/(?<=[A-Z])(?=\W)/', $word);
	}
}
