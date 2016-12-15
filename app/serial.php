<?php

namespace App;

class Serial {

	public function __construct($pattern, $serial, $date) {
		$this->pattern = $pattern;
		$this->serial = $serial;
		$this->date = $date;

	}
}
