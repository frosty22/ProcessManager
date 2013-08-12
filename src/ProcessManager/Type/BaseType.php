<?php

namespace ProcessManager\Type;

/**
 * Base type of all types.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
abstract class BaseType extends \Nette\Object implements IType {


	/**
	 * @var mixed
	 */
	protected $value;


	/**
	 * @var bool
	 */
	private $required = FALSE;


	/**
	 * Set type as required
	 * @param bool $required
	 * @return $this
	 */
	public function setRequired($required = TRUE)
	{
		$this->required = $required;
		return $this;
	}


	/**
	 * Is type required
	 * @return bool
	 */
	public function isRequired()
	{
		return $this->required;
	}


	/**
	 * Sanitize value, default not needed
	 * @param mixed $value
	 * @return mixed
	 */
	public function sanitize($value)
	{
		$this->checkSanitized($value);
		return $value;
	}


	/**
	 * Call before sanitize value
	 * @param mixed $value
	 * @throws \ProcessManager\InvalidArgumentException
	 */
	protected function checkSanitized($value)
	{
		if (!$this->check($value))
			throw new \ProcessManager\InvalidArgumentException("Value is not correct, call 'check' method before sanitize.");
	}


}