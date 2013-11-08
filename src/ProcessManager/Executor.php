<?php

namespace ProcessManager;

use ProcessManager\Process\IProcess;
use ProcessManager\Reader\IReader;
use ProcessManager\Reader\NullableReader;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onReadCollection(IReader $reader, Collection $collection)
 * @method onBeforeProcessCheck(IProcess $process, Collection $collection)
 * @method onBeforeProcessExecute(IProcess $process, Collection $collection)
 * @method onAfterProcessExecute(IProcess $process, Collection $collection, mixed $result)
 *
 */
class Executor extends \Nette\Object {


	/**
	 * Events
	 * @var array
	 */
	public $onReadCollection = array();
	public $onBeforeProcessCheck = array();
	public $onBeforeProcessExecute = array();
	public $onAfterProcessExecute = array();


	/**
	 * @var IReader
	 */
	private $reader;


	/**
	 * @var Execute[]
	 */
	private $executes = array();


	/**
	 * @var array
	 */
	private $collectionKeys = array();


	/**
	 * @param IReader $reader
	 */
	public function __construct(IReader $reader = NULL)
	{
		if (is_null($reader))
			$reader = new NullableReader();

		$this->reader = $reader;
	}


	/**
	 * Add process
	 * @param IProcess $process
	 * @param null|string $namespace
	 * @return Execute
	 */
	public function add(IProcess $process, $namespace = NULL)
	{
		$this->executes[] = $execute = new Execute($process, $namespace);
		return $execute;
	}


	/**
	 * Add keys to all read collections
	 * @param string|array $key
	 * @param mixed $value
	 */
	public function append($key, $value = NULL)
	{
		if (is_array($key)) {
			foreach ($key as $id => $value)
				$this->append($id, $value);
		} else {
			$this->collectionKeys[$key] = $value;
		}
	}


	/**
	 * Do not call directly, use $manager->execute(Executor $executor)
	 * @param ProcessManager $manager
	 */
	public function run(ProcessManager $manager)
	{
		$this->reader->init($manager);
		
		foreach ($this->reader as $collection) {
			$this->onReadCollection($this->reader, $collection);

			foreach ($this->collectionKeys as $key => $value) {
				if (!isset($collection->$key)) $collection->$key = $value;
			}

			foreach ($this->executes as $execute) {
				$execute->run($manager, $this, $collection);
			}
		}
	}


}