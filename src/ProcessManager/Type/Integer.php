<?php

namespace ProcessManager\Type;

use Nette\Utils\Validators;

/**
 * Type for integer (numeric) value
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Integer extends BaseType {


	/**
	 * Check if is integer value
	 * @param string $value
	 * @return bool
	 */
	public function check($value)
	{
		return Validators::isNumericInt($value);
	}


	/**
	 * Convert scalar value to integer
	 * @param string|int $value
	 * @return integer
	 */
	public function sanitize($value)
	{
		$this->checkSanitized($value);
		return (int)$value;
	}

}