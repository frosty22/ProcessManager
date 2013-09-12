<?php

namespace ProcessManager;

use ProcessManager\Converter\IConverter;
use ProcessManager\Process\IProcess;
use ProcessManager\Process\MultiProcess;
use ProcessManager\Reader\IReader;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onBeforeExecute(Executor $executor)
 * @method onBeforeProcessCheck(IProcess $process, Mapper $mapper, Collection $collection)
 * @method onBeforeProcessExecute(IProcess $process, Mapper $mapper, Collection $collection)
 * @method onBeforeConverterCheck(IConverter $process, Mapper $mapper, Collection $collection)
 * @method onBeforeConverterExecute(IConverter $process, Mapper $mapper, Collection $collection)
 * @method onException(ProcessException $exception)
 * @method onReadCollection(IReader $reader, Collection $collection)
 *
 */
class ProcessManager extends \Nette\Object {

	/**
	 * Call before is executed
	 * @var array
	 */
	public $onBeforeExecute = array();


	/**
	 * Call before collection is checked
	 * @var array
	 */
	public $onBeforeProcessCheck = array();


	/**
	 * Call before process is executed
	 * @var array
	 */
	public $onBeforeProcessExecute = array();


	/**
	 * Call before converter is checked
	 * @var array
	 */
	public $onBeforeConverterCheck = array();


	/**
	 * Call before converter is executed
	 * @var array
	 */
	public $onBeforeConverterExecute = array();


	/**
	 * Call on read collection
	 * @var array
	 */
	public $onReadCollection = array();


	/**
	 * @var array
	 */
	public $onException = array();


	/**
	 * Execute process via Executor
	 * @param Executor $executor
	 * @throws InvalidArgumentException
	 * @throws ProcessException
	 */
	public function execute(Executor $executor)
	{
		$this->onBeforeExecute($executor);
		$reader = $executor->getReader();

		if (!$reader instanceof \Iterator)
			throw new InvalidArgumentException('Reader must implement Iterator interface.');

		$this->setupEvents($executor);

		foreach ($reader as $collection) {

			if (!$collection instanceof Collection)
				throw new InvalidArgumentException('Iterator of reader must return only instanceof Collection
													but "'.gettype($collection).'" was returned.');

			$this->onReadCollection($reader, $collection);
			$executor->execute($collection);
		}

	}


	/**
	 * @return MultiProcess
	 */
	public function createMultiProcess()
	{
		return new MultiProcess($this);
	}


	/**
	 * Setup global events to Executor
	 * @param Executor $executor
	 */
	private function setupEvents(Executor $executor)
	{
		foreach ($this->onException as $event) {
			$executor->onException[] = $event;
		}

		foreach ($executor->getConverters() as $execute) {
			foreach ($this->onBeforeConverterCheck as $event)
				$execute->onBeforeCheck[] = $event;

			foreach ($this->onBeforeConverterExecute as $event)
				$execute->onBeforeExecute[] = $event;
		}

		foreach ($executor->getProcesses() as $execute) {
			foreach ($this->onBeforeProcessCheck as $event)
				$execute->onBeforeCheck[] = $event;

			foreach ($this->onBeforeProcessExecute as $event)
				$execute->onBeforeExecute[] = $event;
		}
	}


}