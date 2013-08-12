<?php

namespace ProcessManager\Type;

use ProcessManager\InvalidArgumentException;

/**
 * Type of object
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Object extends BaseType {


	/**
	 * @var string
	 */
	private $className;


	/**
	 * @param string|object $className
	 * @throws InvalidArgumentException
	 */
	public function __construct($className)
	{
		if (is_object($className))
			$className = get_class($className);

		if (!is_string($className))
			throw new InvalidArgumentException("Class name must be string or object.");

		$this->className = $className;
	}


	/**
	 * Get class name
	 * @return string
	 */
	public function getClassName()
	{
		return $this->className;
	}


	/**
	 * Check value
	 * @param mixed $value
	 * @return bool
	 */
	public function check($value)
	{
		return $value instanceof $this->className;
	}

}