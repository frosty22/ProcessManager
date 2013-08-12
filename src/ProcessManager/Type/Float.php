<?php

namespace ProcessManager\Type;
use Nette\Utils\Validators;

/**
 * Type of float, decimal
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Float extends BaseType {


	/**
	 * Check if is float value
	 * @param string|int|float $value
	 * @return bool
	 */
	public function check($value)
	{
		return Validators::isNumeric($value);
	}


	/**
	 * Convert scalar value to float
	 * @param string|int|float $value
	 * @return float
	 */
	public function sanitize($value)
	{
		$this->checkSanitized($value);
		return (float)$value;
	}

}