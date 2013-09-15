<?php

namespace ProcessManager;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onBeforeExecute(Executor $executor)
 * @method onAfterExecute(Executor $executor)
 * @method onReadCollection(IReader $reader, Collection $collection)
 * @method onBeforeProcessCheck(IProcess $process, Collection $collection)
 * @method onBeforeProcessExecute(IProcess $process, Collection $collection)
 * @method onAfterProcessExecute(IProcess $process, Collection $collection)
 *
 */
class ProcessManager extends \Nette\Object {


	/**
	 * Events
	 * @var array
	 */
	public $onBeforeExecute = array();
	public $onAfterExecute = array();
	public $onReadCollection = array();
	public $onBeforeProcessCheck = array();
	public $onBeforeProcessExecute = array();
	public $onAfterProcessExecute = array();


	/**
	 * @param Executor $executor
	 */
	public function execute(Executor $executor)
	{
		$this->onBeforeExecute($executor);
		$this->joinEvents($executor);
		$executor->run();
		$this->onAfterExecute($executor);
	}


	/**
	 * Join events
	 * @param Executor $executor
	 */
	protected function joinEvents(Executor $executor)
	{
		$executor->onReadCollection += $this->onReadCollection;
		$executor->onAfterProcessExecute += $this->onAfterProcessExecute;
		$executor->onBeforeProcessCheck += $this->onBeforeProcessCheck;
		$executor->onBeforeProcessExecute += $this->onBeforeProcessExecute;
	}


}