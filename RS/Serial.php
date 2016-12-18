<?php

namespace RS;

class Serial {

	/**
	 * { Serial class constructor for formatting serial and date }
	 *
	 * @param      <string>  $serial  The serial
	 * @param      <string>  $date    The date
	 */
	public function __construct($serial, $date) {
		$this->serial = $serial;
		$this->date = $date;

	}

}
