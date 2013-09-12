<?php

namespace ProcessManager\DI;

use Nette\Config\CompilerExtension;

/**
 * Process manager installer - extension
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class ProcessManagerExtension extends CompilerExtension {


	private $defaults = array(
		'listeners' => array(

		)
	);


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$processManager = $builder->addDefinition($this->prefix('processManager'))
				->setClass('ProcessManager\ProcessManager');

	}

}