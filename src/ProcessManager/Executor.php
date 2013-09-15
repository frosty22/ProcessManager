<?php

namespace ProcessManager;

use ProcessManager\Process\IProcess;
use ProcessManager\Reader\IReader;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onReadCollection(IReader $reader, Collection $collection)
 * @method onBeforeProcessCheck(IProcess $process, Collection $collection)
 * @method onBeforeProcessExecute(IProcess $process, Collection $collection)
 * @method onAfterProcessExecute(IProcess $process, Collection $collection)
 *
 */
class Executor extends \Nette\Object {


	/**
	 * Events
	 * @var array
	 */
	public $onReadCollection = array();
	public $onBeforeProcessCheck = array();
	public $onBeforeProcessExecute = array();
	public $onAfterProcessExecute = array();


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
			$this->onReadCollection($this->reader, $collection);
			foreach ($this->executes as $execute) {
				$this->joinEvents($execute);
				$execute->run($collection);
			}
		}
	}


	/**
	 * Join events
	 * @param Execute $execute
	 */
	protected function joinEvents(Execute $execute)
	{
		$execute->onAfterExecute += $this->onAfterProcessExecute;
		$execute->onBeforeCheck += $this->onBeforeProcessCheck;
		$execute->onBeforeExecute += $this->onBeforeProcessExecute;
	}

}