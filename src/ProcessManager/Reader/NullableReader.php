<?php

namespace ProcessManager\Reader;
use ProcessManager\Collection;
use ProcessManager\InvalidStateException;

/**
 * Nullable reader
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class NullableReader implements IReader {


	/**
	 * @var bool
	 */
	private $iterator = TRUE;


	/**
	 * @var Collection
	 */
	private $collection;


	/**
	 * @param \ProcessManager\ProcessManager $manager
	 */
	public function init(\ProcessManager\ProcessManager $manager)
	{
		$this->collection = new Collection();
	}


	/**
	 * Rewind collection
	 */
	public function rewind() {
		$this->iterator = TRUE;
	}


	/**
	 * @return Collection
	 * @throws \ProcessManager\InvalidStateException
	 */
	public function current() {
		if (!isset($this->collection))
			throw new InvalidStateException("Do not call reader directly! Only for use with ProcessManager.");

		return $this->collection;
	}


	/**
	 * @return string
	 */
	public function key() {
		return 0;
	}


	/**
	 * Move position next
	 */
	public function next() {
		$this->iterator = FALSE;
	}


	/**
	 * @return bool
	 */
	public function valid() {
		return $this->iterator;
	}

}