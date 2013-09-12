<?php

namespace ProcessManager\Process;

use ProcessManager\Collection;
use ProcessManager\Execute\Process;
use ProcessManager\ExecuteProcess;
use ProcessManager\InvalidCallException;
use ProcessManager\Mapper;
use ProcessManager\ProcessManager;

/**
 * Process for group more processes together.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class MultiProcess extends \Nette\Object implements IProcess {


	/**
	 * @var array
	 */
	private $processes = array();


	/**
	 * @var ProcessManager
	 */
	private $processManager;


	/**
	 * @param ProcessManager $processManager
	 */
	public function __construct(ProcessManager $processManager)
	{
		$this->processManager = $processManager;
	}


	/**
	 * Add process to execute.
	 * @param IProcess $process
	 * @param string|null $subCollection
	 * @return $this
	 */
	public function addProcess(IProcess $process, $subCollection = NULL)
	{
		$this->processes[] = array($process, $subCollection);
		return $this;
	}


	/**
	 * Return required mapper, for data input.
	 * @return \ProcessManager\Mapper
	 */
	public function getRequiredMapper()
	{
		$mapper = new Mapper();

		foreach ($this->processes as $process) {
			if ($process[1] !== NULL) {
				$mapper->addCollection($process[1])
					->setRequired();
			}
		}

		return $mapper;
	}


	/**
	 * @param Collection $collection
	 * @throws \ProcessManager\InvalidCallException
	 * @return mixed|void
	 */
	public function execute(Collection $collection)
	{
		foreach ($this->processes as $process) {
			$execute = new Process($process[0], $process[1]);
			$execute->execute($collection);
		}
	}


}