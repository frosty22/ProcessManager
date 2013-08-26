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
	 * @param string $message
	 */
	public function __construct($name, $message = "")
	{
		$this->name = $name;
		$this->message = $message;
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
	 * @param string $message
	 */
	public function __construct($name, $value, $message = "")
	{
		$this->name = $name;
		$this->value = $value;
		$this->message = $message;
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
