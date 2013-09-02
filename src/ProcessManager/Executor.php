<?php

namespace ProcessManager;

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
	 * @var Execute[]
	 */
	private $processes = array();


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
	 * Add process to execute.
	 * @param IProcess $process
	 * @param string|null $subCollection
	 * @return $this
	 */
	public function addProcess(IProcess $process, $subCollection = NULL)
	{
		$this->processes[] = new Execute($process, $subCollection);
		return $this;
	}


	/**
	 * @return Execute[]
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
	 * Catch exception and return if can be catched
	 * @param ProcessException $exception
	 * @return boolean
	 */
	public function handleException(ProcessException $exception)
	{
		$this->onException($exception);
		return $this->catchException;
	}


}