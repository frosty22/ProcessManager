<?php

namespace ProcessManager\Converter;

use ProcessManager\Process\IProcess;
use ProcessManager\Type\IType;

/**
 * Interface for all converters.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
interface IConverter extends IProcess {


	/**
	 * Returned type of execute method.
	 * @return IType|NULL
	 */
	public function getReturnedType();


}