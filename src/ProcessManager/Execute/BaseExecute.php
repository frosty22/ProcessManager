<?php

namespace ProcessManager\Execute;

use ProcessManager\Collection;
use ProcessManager\InvalidArgumentException;

/**
 * Base of execute.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
abstract class BaseExecute extends \Nette\Object {


	/**
	 * @var string
	 */
	private $namespace;


	/**
	 * Get namespace in collection
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}


	/**
	 * Set namespace in collection
	 * @param string $namespace
	 * @return $this
	 */
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}


	/**
	 * @param Collection $collection
	 * @return Collection
	 * @throws \ProcessManager\InvalidArgumentException
	 */
	protected function getTargetCollection(Collection $collection)
	{
		if ($this->getNamespace() === NULL)
			return $collection;

		$collection = $collection->{$this->getNamespace()};

		if (!$collection instanceof Collection)
			throw new InvalidArgumentException('Required sub collection not found, but "'.gettype($collection).'" was returned.');

		return $collection;
	}


}