<?php

namespace ProcessManager;

// Base core of ProcessManager exception
abstract class Exception extends \Exception { }

// Base logic and runtime of core of ProcessManager exception
abstract class LogicException extends Exception { }
abstract class RuntimeException extends Exception { }

// Base exception for process
abstract class ProcessException extends \Exception { }

// Base logic and runtime expcetion for process
abstract class ProcessLoginException extends ProcessException { }
abstract class ProcessRuntimeException extends ProcessException { }

// Specific exceptions of core of Processanager
class InvalidCallException extends LogicException { }
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
