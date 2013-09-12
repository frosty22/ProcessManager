<?php

namespace ProcessManager\Execute;
use ProcessManager\Collection;

/**
 * Interface for all execute.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
interface IExecute {


	/**
	 * @param Collection $collection
	 * @return mixed
	 */
	public function execute(Collection $collection);

}