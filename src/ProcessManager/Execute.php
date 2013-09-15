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
 * @method onAfterExecute(IProcess $process, Collection $collection)
 *
 */
class Execute extends \Nette\Object {


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
	 * Run on collection
	 * @param Collection $collection
	 * @throws InvalidArgumentException
	 */
	public function run(Collection $collection)
	{
		if ($this->namespace) {
			$targetCollection = $this->getNamespace($collection);
		} else {
			$targetCollection = $collection;
		}

		if (!$targetCollection instanceof Collection)
			throw new InvalidArgumentException('Iterator of reader must return only instanceof Collection
													but "'.gettype($targetCollection).'" was returned.');

		$mapper = $this->process->getRequiredMapper();

		$this->onBeforeCheck($this->process, $targetCollection);
		$mapper->check($targetCollection);

		$this->onBeforeExecute($this->process, $targetCollection);
		$result = $this->process->execute($targetCollection);
		foreach ($this->targets as $target)
			$collection->$target = $result;

		$this->onAfterExecute($this->process, $collection);
	}


	/**
	 * Get namespace
	 * @param Collection $collection
	 * @return NULL|Collection
	 */
	private function getNamespace(Collection $collection)
	{
		$parts = Explode('.', $this->namespace);
		foreach ($parts as $part) {
			if (!$collection->$part instanceof Collection)
				return NULL;
			$collection = $collection->$part;
		}
		return $collection;
	}


}