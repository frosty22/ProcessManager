<?php

namespace ProcessManager\Reader;

use Nette\Utils\Validators;
use ProcessManager\Collection;
use ProcessManager\Reader\IReader;

/**
 * Array reader.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class ArrayReader implements IReader {


	/**
	 * @var int
	 */
	private $position = 0;


	/**
	 * @var array
	 */
	private $array = array();


	/**
	 * @param array $array
	 */
	public function __construct(array $array)
	{
		$this->array = Validators::isList($array) ? $array : array($array);
	}


	/*********** ITERATOR **********/

	public function rewind() {
		$this->position = 0;
	}


	public function current() {
		if (!$this->array[$this->position] instanceof Collection)
			$this->array[$this->position] = new Collection($this->array[$this->position]);

		return $this->array[$this->position];
	}


	public function key() {
		return $this->position;
	}


	public function next() {
		++$this->position;
	}


	public function valid() {
		return isset($this->array[$this->position]);
	}

}