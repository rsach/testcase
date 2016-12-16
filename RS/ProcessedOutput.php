<?php
namespace RS;

class ProcessedOutput {
	public function __construct($data, $status, $message, $code) {
		$this->data = $data;
		$this->status = $status;
		$this->message = $message;
		$this->code = $code;

	}
}