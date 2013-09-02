<?php

namespace ProcessManager;

use ProcessManager\Process\IProcess;

/**
 * Process to execute.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Execute extends \Nette\Object {


	/**
	 * @var IProcess
	 */
	private $process;


	/**
	 * @var string
	 */
	private $namespace;


	/**
	 * @param IProcess $process
	 * @param string|null $namespace Namespace of collection
	 */
	public function __construct(IProcess $process, $namespace = NULL)
	{
		$this->namespace = $namespace;
		$this->process = $process;
	}


	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}


	/**
	 * @return \ProcessManager\Process\IProcess
	 */
	public function getProcess()
	{
		return $this->process;
	}


}