<?php

namespace ProcessManager\Type;
use Nette\Utils\Validators;

/**
 * Type of boolean
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Boolean extends BaseType {


	/**
	 * Check if is bool value
	 * @param bool $value
	 * @return bool
	 */
	public function check($value)
	{
		return is_bool($value);
	}


}