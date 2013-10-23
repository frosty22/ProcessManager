<?php

namespace ProcessManager\Reader;
use ProcessManager\Collection;

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


	public function __construct()
	{
		$this->collection = new Collection();
	}


	public function rewind() {
		$this->iterator = TRUE;
	}


	/**
	 * @return Collection
	 */
	public function current() {
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