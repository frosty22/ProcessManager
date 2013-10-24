<?php

namespace ProcessManager;

use ProcessManager\Process\IProcess;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onBeforeCheck(IProcess $process, Collection $collection)
 * @method onBeforeExecute(IProcess $process, Collection $collection)
 * @method onAfterExecute(IProcess $process, Collection $collection, mixed $result)
 *
 */
class Execute extends \Nette\Object {


	const TARGET = -1;

	/**
	 * Events
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
	 * @var string
	 */
	private $namespace;


	/**
	 * @var array
	 */
	private $targets = array();


	/**
	 * @var array
	 */
	private $conditions = array();


	/**
	 * @param IProcess $process
	 * @param null|string $namespace
	 * @throws InvalidArgumentException
	 */
	public function __construct(IProcess $process, $namespace = NULL)
	{
		$this->namespace = $namespace;

		if (!$process->getRequiredMapper() instanceof Mapper)
			throw new InvalidArgumentException('Method getRequiredMapper of process ' . get_class($process) . ' must
								return Mapper but ' . gettype($process->getRequiredMapper()) . ' return.');

		$this->process = $process;
	}


	/**
	 * Add target
	 * @param string $target
	 * @return $this
	 */
	public function addTarget($target)
	{
		$this->targets[] = $target;
		return $this;
	}


	/**
	 * Add condition for run - IF collection target = VALUE, run
	 * @param string $target
	 * @param mixed $value
	 */
	public function addCondition($target, $value = NULL)
	{
		$this->conditions[$target] = $value;
	}


	/**
	 * Run on collection
	 * @param ProcessManager $manager
	 * @param Executor $executor
	 * @param Collection $collection
	 * @return mixed|null
	 * @throws InvalidArgumentException
	 */
	public function run(ProcessManager $manager, Executor $executor, Collection $collection)
	{
		if ($this->canRun($collection) === FALSE)
			return NULL;

		if ($this->namespace) {
			$targetCollection = $collection[$this->namespace];
		} else {
			$targetCollection = $collection;
		}

		if (!$targetCollection instanceof Collection)
			throw new InvalidArgumentException('Iterator of reader must return only instanceof Collection
													but "'.gettype($targetCollection).'" was returned.');

		$mapper = $this->process->getRequiredMapper();


		$this->onBeforeCheck($this->process, $targetCollection);
		$executor->onBeforeProcessCheck($this->process, $targetCollection);
		$manager->onBeforeProcessCheck($this->process, $targetCollection);

		$mapper->check($targetCollection);

		$this->onBeforeExecute($this->process, $targetCollection);
		$executor->onBeforeProcessExecute($this->process, $targetCollection);
		$manager->onBeforeProcessExecute($this->process, $targetCollection);

		$result = $this->process->execute($targetCollection);
		foreach ($this->targets as $target)
			$collection->$target = $result;

		$this->onAfterExecute($this->process, $collection, $result);
		$executor->onAfterProcessExecute($this->process, $collection, $result);
		$manager->onAfterProcessExecute($this->process, $collection, $result);

		return $result;
	}


	/**
	 * Check if can run process
	 * @param Collection $collection
	 * @return bool
	 */
	private function canRun(Collection $collection)
	{
		foreach ($this->conditions as $target => $value) {

			if ($target === self::TARGET) {
				foreach ($this->targets as $target)
					if (!$this->compare($collection->$target, $value))
						return FALSE;
			}
			elseif (!$this->compare($collection->$target, $value))
				return FALSE;

		}
		return TRUE;
	}


	/**
	 * Compare values
	 * @param mixed $targetValue
	 * @param mixed $value
	 * @return bool
	 */
	private function compare($targetValue, $value)
	{
		// TODO: Kontrola podle typů, přenést do objektu Condition ?
		return ($targetValue === $value);
	}

}