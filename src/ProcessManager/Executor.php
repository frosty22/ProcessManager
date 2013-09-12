<?php

namespace ProcessManager;

use ProcessManager\Converter\IConverter;
use ProcessManager\Execute\Converter;
use ProcessManager\Execute\IExecute;
use ProcessManager\Execute\Process;
use ProcessManager\Process\IProcess;
use ProcessManager\Reader\IReader;

/**
 * Process executor.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onException(ProcessException $exception)
 *
 */
class Executor extends \Nette\Object {


	/**
	 * @var array
	 */
	public $onException = array();


	/**
	 * @var IReader
	 */
	private $reader;


	/**
	 * @var Process[]
	 */
	private $processes = array();


	/**
	 * @var Converter[]
	 */
	private $converters = array();


	/**
	 * @var boolean
	 */
	private $catchException = FALSE;


	/**
	 * @param IReader $reader
	 */
	public function __construct(IReader $reader)
	{
		$this->reader = $reader;
	}


	/**
	 * @param boolean $catchException
	 */
	public function setCatchException($catchException)
	{
		$this->catchException = $catchException;
	}


	/**
	 * @return boolean
	 */
	public function getCatchException()
	{
		return $this->catchException;
	}


	/**
	 * Add converter
	 * @param IConverter $converter
	 * @param null|string $namespace
	 * @param null|array|string $target
	 * @param bool $required
	 * @throws InvalidArgumentException
	 * @return Converter
	 */
	public function addConverter(IConverter $converter, $namespace = NULL, $target = NULL, $required = FALSE)
	{
		$execute = new Converter($converter, $namespace, $required);
		$execute->setTarget($target);
		$this->converters[] = $execute;
		return $execute;
	}


	/**
	 * Add process to execute.
	 * @param IProcess $process
	 * @param string|null $namespace
	 * @return Process
	 */
	public function addProcess(IProcess $process, $namespace = NULL)
	{
		$execute = new Process($process, $namespace);
		$this->processes[] = $execute;
		return $execute;
	}


	/**
	 * @return Converter[]
	 */
	public function getConverters()
	{
		return $this->converters;
	}


	/**
	 * @return Process[]
	 */
	public function getProcesses()
	{
		return $this->processes;
	}


	/**
	 * @return IReader
	 */
	public function getReader()
	{
		return $this->reader;
	}


	/**
	 * @param Collection $collection
	 * @throws ProcessException
	 */
	public function execute(Collection $collection)
	{
		foreach ($this->getConverters() as $execute) {
			$this->run($execute, $collection);
		}

		foreach ($this->getProcesses() as $execute) {
			$this->run($execute, $collection);
		}
	}


	/**
	 * @param IExecute $execute
	 * @param Collection $collection
	 * @throws \Exception|ProcessException
	 */
	protected function run(IExecute $execute, Collection $collection)
	{
		try {
			$execute->execute($collection);
		} catch (ProcessException $e) {
			$this->onException($e);
			if (!$this->handleException($e))
				throw $e;
		}
	}


	/**
	 * Call event on exception and return if can be catch
	 * @param ProcessException $exception
	 * @return boolean
	 */
	protected function handleException(ProcessException $exception)
	{
		$this->onException($exception);
		return $this->catchException;
	}


}