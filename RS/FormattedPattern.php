<?php
namespace RS;
class FormattedPattern {
	/**
	 * { it's constructor which gives process pattern }
	 *
	 * @param      <string>  $prePattern   The pre pattern
	 * @param      <string>  $postPattern  The post pattern
	 * @param      <string>  $date         The date
	 * @param      <string>  $order        The order
	 */
	public function __construct($prePattern, $postPattern, $date, $order) {
		$this->prePattern = $prePattern;
		$this->postPattern = $postPattern;
		$this->date = $date;
		$this->order = $order;

	}
}