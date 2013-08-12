<?php

namespace ProcessManager\Type;

/**
 * Type for text (string) value
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Text extends BaseType {


	/**
	 * Check if is scalar value
	 * @param string $value
	 * @return bool
	 */
	public function check($value)
	{
		return is_scalar($value);
	}


	/**
	 * Convert scalar value to string
	 * @param mixed $value
	 * @return string
	 */
	public function sanitize($value)
	{
		$this->checkSanitized($value);
		return (string)$value;
	}

}