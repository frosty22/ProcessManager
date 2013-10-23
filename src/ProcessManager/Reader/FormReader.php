<?php

namespace ProcessManager\Reader;
use ProcessManager\Collection;

/**
 * Reader for Nette\Forms\Form
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class FormReader extends \Nette\FreezableObject implements IReader {


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
	 * @param string $from Source key name
	 * @param string $to Target key name
	 * @return $this
	 */
	public function rename($from, $to)
	{
		$this->copy($from, $to);
		$this->remove($from);
		return $this;
	}


	/**
	 * Copy key
	 * @param string $from Source key
	 * @param string $to Target key
	 * @return $this
	 */
	public function copy($from, $to)
	{
		$this->updating();

		if (isset($this->values[$from])) {
			$this->values[$to] = $this->values[$from];
		}

		return $this;
	}


	/**
	 * Remove key
	 * @param string $key Key name
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