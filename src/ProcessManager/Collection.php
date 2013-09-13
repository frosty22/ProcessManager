<?php

namespace ProcessManager;

/**
 * Represent of data collection.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Collection implements \ArrayAccess {


	/**
	 * @var array
	 */
	private $values = array();


	/**
	 * @param array $array
	 */
	public function __construct(array $array = array())
	{
		foreach ($array as $key => $value)
			$this->$key = $value;
	}


	/**
	 * Check if key exist
	 * @param string|int $name
	 * @return bool
	 */
	public function exist($name)
	{
		return isset($this->values[$name]);
	}


	/**
	 * Get value
	 * @param string|int $name
	 * @return mixed|NULL
	 */
	public function __get($name)
	{
		return isset($this->values[$name]) ? $this->values[$name] : NULL;
	}


	/**
	 * Set value
	 * @param string|int $name
	 * @param mixed $value
	 * @throws InvalidArgumentException
	 */
	public function __set($name, $value)
	{
		if (!is_string($name) && !is_integer($name))
			throw new InvalidArgumentException("Name of type must be string or number.");

		if (strpos($name, ".")) {
			$key = mb_substr($name, 0, mb_strpos($name, "."));
			$name = mb_substr($name, mb_strlen($key) + 1);

			if (!isset($this->values[$key]))
				$this->values[$key] = new Collection();

			$this->values[$key]->$name = $value;
		} else {
			$this->values[$name] = $value;
		}
	}


	/**
	 * Check if exist key
	 * @param string|int $name
	 * @return bool
	 */
	public function __isset($name)
	{
		return $this->exist($name);
	}


	/**
	 * @param string|int $name
	 */
	public function __unset($name)
	{
		unset($this->values[$name]);
	}


	/**
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return $this->exist($offset);
	}


	/**
	 * @param string|int $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->__set($offset, $value);
	}


	/**
	 * @param string|int $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->__get($offset);
	}


	/**
	 * @param string|int $offset
	 */
	public function offsetUnset($offset)
	{
		$this->__unset($offset);
	}

}