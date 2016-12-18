<?php
namespace RS;

class ProcessedOutput {

	/**
	 * { constructor for processed output class that is used in jsend response }
	 *
	 * @param      <string>  $data     The data
	 * @param      <string>  $status   The status
	 * @param      <string>  $message  The message
	 * @param      <integer>  $code     The code
	 */
	public function __construct($data, $status, $message, $code) {
		$this->data = $data;
		$this->status = $status;
		$this->message = $message;
		$this->code = $code;

	}
}