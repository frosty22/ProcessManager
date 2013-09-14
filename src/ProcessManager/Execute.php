<?php

namespace ProcessManager;

use ProcessManager\Process\IProcess;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Execute {


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
	 * @param string $namespace
	 */
	public function __construct(IProcess $process, $namespace = NULL)
	{
		$this->namespace = $namespace;
		$this->process = $process;
	}


	/**
	 * Add target
	 * @param string $target
	 * @return $this
	 */
	public function addTarget($target)
	{
		$this->targets = $target;
		return $this;
	}


	/**
	 * @param Collection $collection
	 */
	public function run(Collection $collection)
	{
		$mapper = $this->process->getRequiredMapper();
		$mapper->check($collection);

		if ($this->namespace) {
			$targetCollection = $collection->{$this->namespace};
		} else {
			$targetCollection = $collection;
		}

		if (!$targetCollection instanceof Collection)
			throw new InvalidArgumentException('Iterator of reader must return only instanceof Collection
													but "'.gettype($targetCollection).'" was returned.');

		$result = $this->process->execute($targetCollection);
		foreach ($this->targets as $target)
			$collection->$target = $result;
	}


}