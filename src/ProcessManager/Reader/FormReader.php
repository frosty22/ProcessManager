<?php

namespace ProcessManager\Reader;
use ProcessManager\Collection;

/**
 *
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class FormReader extends \Nette\FreezableObject implements IReader, \Iterator {


	/**
	 * @var Collection
	 */
	private $collection;


	/**
	 * @var array
	 */
	private $values;


	/**
	 * @param \Nette\Forms\Form $form
	 */
	public function __construct(\Nette\Forms\Form $form)
	{
		$this->values = $form->getValues(TRUE);
	}


	/**
	 * @return Collection
	 */
	public function getCollection()
	{
		if (is_null($this->collection)) {
			$this->freeze();
			$this->collection = new Collection($this->values);
		}
		return $this->collection;
	}


	/**
	 * Rename key
	 * @param string $from
	 * @param string $to
	 * @return $this
	 */
	public function rename($from, $to)
	{
		$this->updating();

		if (isset($this->values[$from])) {
			$this->values[$to] = $this->values[$from];
			unset($this->values[$from]);
		}

		return $this;
	}


	/**
	 * Remove key
	 * @param string $key
	 * @return $this
	 */
	public function remove($key)
	{
		$this->updating();
		unset($this->values[$key]);
		return $this;
	}


	/*********** ITERATOR **********/


	/**
	 * @var bool
	 */
	private $iterator = TRUE;


	/**
	 * @return Collection
	 */
	public function rewind() {
		$this->iterator = TRUE;
	}


	/**
	 * @return Collection
	 */
	public function current() {
		return $this->getCollection();
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