<?php

namespace ProcessManager\Execute;

use ProcessManager\Collection;
use ProcessManager\InvalidArgumentException;
use ProcessManager\InvalidValueException;
use ProcessManager\Mapper;
use ProcessManager\MissingKeyException;
use ProcessManager\Process\IProcess;

/**
 * Process to execute.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onBeforeCheck(IProcess $converter, Mapper $mapper, Collection $collection)
 * @method onBeforeExecute(IProcess $converter, Mapper $mapper, Collection $collection)
 * @method onAfterExecute(IProcess $converter, Mapper $mapper, Collection $collection)
 *
 */
class Process extends BaseExecute implements IExecute {

	/**
	 * Available events
	 * @var array
	 */
	public $onBeforeCheck = array();
	public $onBeforeExecute = array();
	public $onAfterExecute = array();


	/**
	 * @var IProcess
	 */
	private $process;


	/**
	 * @param IProcess $process
	 * @param string|null $namespace Namespace of collection
	 * @throws InvalidArgumentException
	 */
	public function __construct(IProcess $process, $namespace = NULL)
	{
		$this->setNamespace($namespace);
		$this->process = $process;

		$mapper = $process->getRequiredMapper();
		if (!$mapper instanceof Mapper)
			throw new InvalidArgumentException('Method "getRequiredMapper" of process "' . get_class($process) . '"
							must return instance of ProcessManager\Mapper but "'.gettype($mapper).'" was returned.');
	}


	/**
	 * Get process
	 * @return IProcess
	 */
	public function getProcess()
	{
		return $this->process;
	}


	/**
	 * Execute process on collection.
	 * @param Collection $collection
	 * @throws InvalidArgumentException
	 * @throws InvalidValueException
	 * @throws MissingKeyException
	 * @return mixed
	 */
	public function execute(Collection $collection)
	{
		$mapper = $this->process->getRequiredMapper();

		$collection = $this->getTargetCollection($collection);

		$this->onBeforeCheck($this->process, $mapper, $collection);
		$mapper->check($collection);

		$this->onBeforeExecute($this->process, $mapper, $collection);
		$result = $this->process->execute($collection);
		$this->onAfterExecute($this->process, $mapper, $collection);

		return $result;
	}


}