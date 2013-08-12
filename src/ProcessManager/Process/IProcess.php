<?php

namespace ProcessManager\Process;

use ProcessManager\Collection;

/**
 * Interface for all processes.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
interface IProcess {


	/**
	 * Return required mapper, for data input.
	 * @return \ProcessManager\Mapper
	 */
	public function getRequiredMapper();


	/**
	 * @param Collection $collection
	 * @return mixed
	 */
	public function execute(Collection $collection);

}