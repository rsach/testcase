<?php
namespace RS;
class FormattedPattern {

	public function __construct($prePattern, $postPattern, $date, $order) {
		$this->prePattern = $prePattern;
		$this->postPattern = $postPattern;
		$this->date = $date;
		$this->order = $order;

	}
}