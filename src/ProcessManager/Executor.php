<?php

namespace ProcessManager;

use ProcessManager\Process\IProcess;
use ProcessManager\Reader\IReader;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Executor extends \Nette\Object {


	/**
	 * @var IReader
	 */
	private $reader;


	/**
	 * @var Execute[]
	 */
	private $executes = array();


	/**
	 * @param IReader $reader
	 */
	public function __construct(IReader $reader)
	{
		$this->reader = $reader;
	}


	/**
	 * Add process
	 * @param IProcess $process
	 * @param string|null $namespace
	 * @return Execute
	 */
	public function add(IProcess $process, $namespace = NULL)
	{
		$this->executes[] = $execute = new Execute($process, $namespace);
		return $execute;
	}


	/**
	 * Run
	 */
	public function run()
	{
		foreach ($this->reader as $collection) {
			foreach ($this->executes as $execute) {
				$execute->run($collection);
			}
		}
	}


}