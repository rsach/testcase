<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SerialConverter extends Controller {

	public function converter(Request $request) {

		// $this->validate($request, [
		// 	'serial' => 'required',
		// 	'date' => 'required',
		// ]);

		$serial = $request->serial;
		$date = $request->date;

		$ser = new App\Serial($serial, $date);

		return $ser->json();

	}
}
