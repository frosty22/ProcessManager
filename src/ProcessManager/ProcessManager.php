<?php

namespace ProcessManager;

use ProcessManager\Process\IProcess;
use ProcessManager\Process\MultiProcess;
use ProcessManager\Reader\IReader;

/**
 *
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onBeforeCheck(IProcess $process, Mapper $mapper, Collection $collection)
 * @method onBeforeExecute(IProcess $process, Mapper $mapper, Collection $collection)
 *
 */
class ProcessManager extends \Nette\Object {


	/**
	 * Call before collection is checked
	 * @var array
	 */
	public $onBeforeCheck = array();


	/**
	 * Call before process is executed
	 * @var array
	 */
	public $onBeforeExecute = array();


	/**
	 * Execute process via Executor
	 * @param Executor $executor
	 */
	public function execute(Executor $executor)
	{
		$reader = $executor->getReader();

		if (!$reader instanceof \Iterator)
			throw new InvalidArgumentException('Reader must implement Iterator interface.');

		foreach ($reader as $collection) {

			if (!$collection instanceof Collection)
				throw new InvalidArgumentException('Iterator of reader must return only instanceof Collection
													but "'.gettype($collection).'" was returned.');

			foreach ($executor->getProcesses() as $execute) {

				if ($execute->getNamespace() !== NULL) {
					$processCollection = $collection->{$execute->getNamespace()};

					if (!$processCollection instanceof Collection)
						throw new InvalidArgumentException('Required sub collection not found, but
														"'.gettype($processCollection).'" was returned.');

				} else {
					$processCollection = $collection;
				}

				try {
					$this->executeProcess($execute->getProcess(), $processCollection);
				} catch (ProcessException $e) {
					if (!$executor->handleException($e))
						throw $e;
				}


			}

		}

	}


	/**
	 * Execute process on collection.
	 * @param IProcess $process
	 * @param Collection $collection
	 * @throws InvalidArgumentException
	 */
	public function executeProcess(IProcess $process, Collection $collection)
	{
		$mapper = $process->getRequiredMapper();

		if (!$mapper instanceof Mapper)
			throw new InvalidArgumentException('Method "getRequiredMapper" of process must return instance of ProcessManager\Mapper
			 									but "'.gettype($collection).'" was returned.');

		$this->onBeforeCheck($process, $mapper, $collection);
		$mapper->check($collection);

		$this->onBeforeExecute($process, $mapper, $collection);
		$process->execute($collection);
	}


	/**
	 * @return MultiProcess
	 */
	public function createMultiProcess()
	{
		return new MultiProcess($this);
	}


}