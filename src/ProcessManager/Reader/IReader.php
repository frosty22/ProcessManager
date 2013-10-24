<?php

namespace ProcessManager\Reader;

/**
 * Interface for all readers
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
interface IReader extends \Iterator {


	/**
	 * Initialize read, can provides external source loading etc.
	 * Is called one times before run, in $executor->execute()
	 * @param \ProcessManager\ProcessManager $manager
	 * @return void
	 */
	public function init(\ProcessManager\ProcessManager $manager);


}