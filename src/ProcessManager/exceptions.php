<?php

namespace ProcessManager;

abstract class Exception extends \Exception { }

abstract class LogicException extends Exception { }
abstract class RuntimeException extends Exception { }

class InvalidArgumentException extends LogicException { }
class InvalidStateException extends LogicException { }

class MissingKeyException extends RuntimeException {

	/**
	 * @var string
	 */
	private $name;


	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

}


class InvalidValueException extends RuntimeException {


	/**
	 * @var string
	 */
	private $name;


	/**
	 * @var string
	 */
	private $value;


	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}


	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

}
